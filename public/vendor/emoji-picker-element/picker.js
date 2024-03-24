import Database from './database.js';

function noop() { }
function run(fn) {
    return fn();
}
function blank_object() {
    return Object.create(null);
}
function run_all(fns) {
    fns.forEach(run);
}
function is_function(thing) {
    return typeof thing === 'function';
}
function safe_not_equal(a, b) {
    return a != a ? b == b : a !== b || ((a && typeof a === 'object') || typeof a === 'function');
}
function is_empty(obj) {
    return Object.keys(obj).length === 0;
}
function action_destroyer(action_result) {
    return action_result && is_function(action_result.destroy) ? action_result.destroy : noop;
}

function append(target, node) {
    target.appendChild(node);
}
function insert(target, node, anchor) {
    target.insertBefore(node, anchor || null);
}
function detach(node) {
    node.parentNode.removeChild(node);
}
function element(name) {
    return document.createElement(name);
}
function text(data) {
    return document.createTextNode(data);
}
function listen(node, event, handler, options) {
    node.addEventListener(event, handler, options);
    return () => node.removeEventListener(event, handler, options);
}
function attr(node, attribute, value) {
    if (value == null)
        node.removeAttribute(attribute);
    else if (node.getAttribute(attribute) !== value)
        node.setAttribute(attribute, value);
}
function children(element) {
    return Array.from(element.childNodes);
}
function set_data(text, data) {
    data = '' + data;
    if (text.wholeText !== data)
        text.data = data;
}
function set_input_value(input, value) {
    input.value = value == null ? '' : value;
}
function set_style(node, key, value, important) {
    node.style.setProperty(key, value, important ? 'important' : '');
}

let current_component;
function set_current_component(component) {
    current_component = component;
}
function get_current_component() {
    if (!current_component)
        throw new Error('Function called outside component initialization');
    return current_component;
}
function onMount(fn) {
    get_current_component().$$.on_mount.push(fn);
}
function onDestroy(fn) {
    get_current_component().$$.on_destroy.push(fn);
}

const dirty_components = [];
const binding_callbacks = [];
const render_callbacks = [];
const flush_callbacks = [];
const resolved_promise = Promise.resolve();
let update_scheduled = false;
function schedule_update() {
    if (!update_scheduled) {
        update_scheduled = true;
        resolved_promise.then(flush);
    }
}
function tick() {
    schedule_update();
    return resolved_promise;
}
function add_render_callback(fn) {
    render_callbacks.push(fn);
}
let flushing = false;
const seen_callbacks = new Set();
function flush() {
    if (flushing)
        return;
    flushing = true;
    do {
        // first, call beforeUpdate functions
        // and update components
        for (let i = 0; i < dirty_components.length; i += 1) {
            const component = dirty_components[i];
            set_current_component(component);
            update(component.$$);
        }
        set_current_component(null);
        dirty_components.length = 0;
        while (binding_callbacks.length)
            binding_callbacks.pop()();
        // then, once components are updated, call
        // afterUpdate functions. This may cause
        // subsequent updates...
        for (let i = 0; i < render_callbacks.length; i += 1) {
            const callback = render_callbacks[i];
            if (!seen_callbacks.has(callback)) {
                // ...so guard against infinite loops
                seen_callbacks.add(callback);
                callback();
            }
        }
        render_callbacks.length = 0;
    } while (dirty_components.length);
    while (flush_callbacks.length) {
        flush_callbacks.pop()();
    }
    update_scheduled = false;
    flushing = false;
    seen_callbacks.clear();
}
function update($$) {
    if ($$.fragment !== null) {
        $$.update();
        run_all($$.before_update);
        const dirty = $$.dirty;
        $$.dirty = [-1];
        $$.fragment && $$.fragment.p($$.ctx, dirty);
        $$.after_update.forEach(add_render_callback);
    }
}
const outroing = new Set();
function transition_in(block, local) {
    if (block && block.i) {
        outroing.delete(block);
        block.i(local);
    }
}

const globals = (typeof window !== 'undefined'
    ? window
    : typeof globalThis !== 'undefined'
        ? globalThis
        : global);

function destroy_block(block, lookup) {
    block.d(1);
    lookup.delete(block.key);
}
function update_keyed_each(old_blocks, dirty, get_key, dynamic, ctx, list, lookup, node, destroy, create_each_block, next, get_context) {
    let o = old_blocks.length;
    let n = list.length;
    let i = o;
    const old_indexes = {};
    while (i--)
        old_indexes[old_blocks[i].key] = i;
    const new_blocks = [];
    const new_lookup = new Map();
    const deltas = new Map();
    i = n;
    while (i--) {
        const child_ctx = get_context(ctx, list, i);
        const key = get_key(child_ctx);
        let block = lookup.get(key);
        if (!block) {
            block = create_each_block(key, child_ctx);
            block.c();
        }
        else if (dynamic) {
            block.p(child_ctx, dirty);
        }
        new_lookup.set(key, new_blocks[i] = block);
        if (key in old_indexes)
            deltas.set(key, Math.abs(i - old_indexes[key]));
    }
    const will_move = new Set();
    const did_move = new Set();
    function insert(block) {
        transition_in(block, 1);
        block.m(node, next);
        lookup.set(block.key, block);
        next = block.first;
        n--;
    }
    while (o && n) {
        const new_block = new_blocks[n - 1];
        const old_block = old_blocks[o - 1];
        const new_key = new_block.key;
        const old_key = old_block.key;
        if (new_block === old_block) {
            // do nothing
            next = new_block.first;
            o--;
            n--;
        }
        else if (!new_lookup.has(old_key)) {
            // remove old block
            destroy(old_block, lookup);
            o--;
        }
        else if (!lookup.has(new_key) || will_move.has(new_key)) {
            insert(new_block);
        }
        else if (did_move.has(old_key)) {
            o--;
        }
        else if (deltas.get(new_key) > deltas.get(old_key)) {
            did_move.add(new_key);
            insert(new_block);
        }
        else {
            will_move.add(old_key);
            o--;
        }
    }
    while (o--) {
        const old_block = old_blocks[o];
        if (!new_lookup.has(old_block.key))
            destroy(old_block, lookup);
    }
    while (n)
        insert(new_blocks[n - 1]);
    return new_blocks;
}
function mount_component(component, target, anchor) {
    const { fragment, on_mount, on_destroy, after_update } = component.$$;
    fragment && fragment.m(target, anchor);
    // onMount happens before the initial afterUpdate
    add_render_callback(() => {
        const new_on_destroy = on_mount.map(run).filter(is_function);
        if (on_destroy) {
            on_destroy.push(...new_on_destroy);
        }
        else {
            // Edge case - component was destroyed immediately,
            // most likely as a result of a binding initialising
            run_all(new_on_destroy);
        }
        component.$$.on_mount = [];
    });
    after_update.forEach(add_render_callback);
}
function destroy_component(component, detaching) {
    const $$ = component.$$;
    if ($$.fragment !== null) {
        run_all($$.on_destroy);
        $$.fragment && $$.fragment.d(detaching);
        // TODO null out other refs, including component.$$ (but need to
        // preserve final state?)
        $$.on_destroy = $$.fragment = null;
        $$.ctx = [];
    }
}
function make_dirty(component, i) {
    if (component.$$.dirty[0] === -1) {
        dirty_components.push(component);
        schedule_update();
        component.$$.dirty.fill(0);
    }
    component.$$.dirty[(i / 31) | 0] |= (1 << (i % 31));
}
function init(component, options, instance, create_fragment, not_equal, props, dirty = [-1]) {
    const parent_component = current_component;
    set_current_component(component);
    const prop_values = options.props || {};
    const $$ = component.$$ = {
        fragment: null,
        ctx: null,
        // state
        props,
        update: noop,
        not_equal,
        bound: blank_object(),
        // lifecycle
        on_mount: [],
        on_destroy: [],
        before_update: [],
        after_update: [],
        context: new Map(parent_component ? parent_component.$$.context : []),
        // everything else
        callbacks: blank_object(),
        dirty,
        skip_bound: false
    };
    let ready = false;
    $$.ctx = instance
        ? instance(component, prop_values, (i, ret, ...rest) => {
            const value = rest.length ? rest[0] : ret;
            if ($$.ctx && not_equal($$.ctx[i], $$.ctx[i] = value)) {
                if (!$$.skip_bound && $$.bound[i])
                    $$.bound[i](value);
                if (ready)
                    make_dirty(component, i);
            }
            return ret;
        })
        : [];
    $$.update();
    ready = true;
    run_all($$.before_update);
    // `false` as a special case of no DOM component
    $$.fragment = create_fragment ? create_fragment($$.ctx) : false;
    if (options.target) {
        if (options.hydrate) {
            const nodes = children(options.target);
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            $$.fragment && $$.fragment.l(nodes);
            nodes.forEach(detach);
        }
        else {
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            $$.fragment && $$.fragment.c();
        }
        if (options.intro)
            transition_in(component.$$.fragment);
        mount_component(component, options.target, options.anchor);
        flush();
    }
    set_current_component(parent_component);
}
let SvelteElement;
if (typeof HTMLElement === 'function') {
    SvelteElement = class extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });
        }
        connectedCallback() {
            // @ts-ignore todo: improve typings
            for (const key in this.$$.slotted) {
                // @ts-ignore todo: improve typings
                this.appendChild(this.$$.slotted[key]);
            }
        }
        attributeChangedCallback(attr, _oldValue, newValue) {
            this[attr] = newValue;
        }
        $destroy() {
            destroy_component(this, 1);
            this.$destroy = noop;
        }
        $on(type, callback) {
            // TODO should this delegate to addEventListener?
            const callbacks = (this.$$.callbacks[type] || (this.$$.callbacks[type] = []));
            callbacks.push(callback);
            return () => {
                const index = callbacks.indexOf(callback);
                if (index !== -1)
                    callbacks.splice(index, 1);
            };
        }
        $set($$props) {
            if (this.$$set && !is_empty($$props)) {
                this.$$.skip_bound = true;
                this.$$set($$props);
                this.$$.skip_bound = false;
            }
        }
    };
}

var enI18n = {
  categoriesLabel: 'Categories',
  emojiUnsupportedMessage: 'Your browser does not support color emoji.',
  favoritesLabel: 'Favorites',
  loadingMessage: 'Loading…',
  networkErrorMessage: 'Could not load emoji. Try refreshing.',
  regionLabel: 'Emoji picker',
  searchDescription: 'When search results are available, press up or down to select and enter to choose.',
  searchLabel: 'Search',
  searchResultsLabel: 'Search results',
  skinToneDescription: 'When expanded, press up or down to select and enter to choose.',
  skinToneLabel: 'Choose a skin tone (currently {skinTone})',
  skinTonesLabel: 'Skin tones',
  skinTones: [
    'Default',
    'Light',
    'Medium-Light',
    'Medium',
    'Medium-Dark',
    'Dark'
  ],
  categories: {
    custom: 'Custom',
    'smileys-emotion': 'Smileys and emoticons',
    'people-body': 'People and body',
    'animals-nature': 'Animals and nature',
    'food-drink': 'Food and drink',
    'travel-places': 'Travel and places',
    activities: 'Activities',
    objects: 'Objects',
    symbols: 'Symbols',
    flags: 'Flags'
  }
};

// via https://unpkg.com/browse/emojibase-data@6.0.0/meta/groups.json
const allGroups = [
  [-1, '✨', 'custom'],
  [0, '😀', 'smileys-emotion'],
  [1, '👋', 'people-body'],
  [3, '🐱', 'animals-nature'],
  [4, '🍎', 'food-drink'],
  [5, '🏠️', 'travel-places'],
  [6, '⚽', 'activities'],
  [7, '📝', 'objects'],
  [8, '⛔️', 'symbols'],
  [9, '🏁', 'flags']
].map(([id, emoji, name]) => ({ id, emoji, name }));

const groups = allGroups.slice(1);
const customGroup = allGroups[0];

const DEFAULT_DATA_SOURCE = 'https://cdn.jsdelivr.net/npm/emoji-picker-element-data@^1/en/emojibase/data.json';
const DEFAULT_LOCALE = 'en';

const MIN_SEARCH_TEXT_LENGTH = 2;
const NUM_SKIN_TONES = 6;

/* istanbul ignore next */
const rIC = typeof requestIdleCallback === 'function' ? requestIdleCallback : setTimeout;

// check for ZWJ (zero width joiner) character
function hasZwj (emoji) {
  return emoji.unicode.includes('\u200d')
}

// @rollup/plugin-strip doesn't properly strip performance.mark/measure

function stop (str) {
}

// Find one good representative emoji to test. Ideally it should have color in the center.
// For some inspiration, see: https://about.gitlab.com/blog/2018/05/30/journey-in-native-unicode-emoji/
const versionsAndTestEmoji = {
  '😃': 0.6,
  '😐️': 0.7,
  '😀': 1,
  '👁️‍🗨️': 2,
  '🤣': 3,
  '👱‍♀️': 4,
  '🤩': 5,
  '🥰': 11, // smiling face with hearts
  '🥻': 12, // sari
  '🧑‍🦰': 12.1, // person: red hair
  '🥲': 13 // smiling face with tear
};

const TIMEOUT_BEFORE_LOADING_MESSAGE = 1000; // 1 second
const DEFAULT_SKIN_TONE_EMOJI = '🖐️';
const DEFAULT_NUM_COLUMNS = 8;

// Based on https://fivethirtyeight.com/features/the-100-most-used-emojis/ and
// https://blog.emojipedia.org/facebook-reveals-most-and-least-used-emojis/ with
// a bit of my own curation. (E.g. avoid the "OK" gesture because of connotations:
// https://emojipedia.org/ok-hand/)
const MOST_COMMONLY_USED_EMOJI = [
  '😊',
  '😒',
  '♥️',
  '👍️',
  '😍',
  '😂',
  '😭',
  '☺️',
  '😔',
  '😩',
  '😏',
  '💕',
  '🙌',
  '😘'
];

const FONT_FAMILY = '"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol",' +
  '"Twemoji Mozilla","Noto Color Emoji","EmojiOne Color","Android Emoji",sans-serif';

// Test if an emoji is supported by rendering it to canvas and checking that the color is not black

const getTextFeature = (text, color) => {
  try {
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = 1;

    const ctx = canvas.getContext('2d');
    ctx.textBaseline = 'top';
    ctx.font = `100px ${FONT_FAMILY}`;
    ctx.fillStyle = color;
    ctx.scale(0.01, 0.01);
    ctx.fillText(text, 0, 0);

    return ctx.getImageData(0, 0, 1, 1).data
  } catch (e) { /* ignore, return undefined */ }
};

const compareFeatures = (feature1, feature2) => {
  const feature1Str = [...feature1].join(',');
  const feature2Str = [...feature2].join(',');
  // This is RGBA, so for 0,0,0, we are checking that the first RGB is not all zeroes.
  // Most of the time when unsupported this is 0,0,0,0, but on Chrome on Mac it is
  // 0,0,0,61 - there is a transparency here.
  return feature1Str === feature2Str && !feature1Str.startsWith('0,0,0,')
};

function testColorEmojiSupported (text) {
  // Render white and black and then compare them to each other and ensure they're the same
  // color, and neither one is black. This shows that the emoji was rendered in color.
  const feature1 = getTextFeature(text, '#000');
  const feature2 = getTextFeature(text, '#fff');
  return feature1 && feature2 && compareFeatures(feature1, feature2)
}

// rather than check every emoji ever, which would be expensive, just check some representatives from the

function determineEmojiSupportLevel () {
  let res;
  for (const [emoji, version] of Object.entries(versionsAndTestEmoji)) {
    /* istanbul ignore else */
    if (testColorEmojiSupported(emoji)) {
      res = version;
    } else {
      break
    }
  }
  return res
}

// @rollup/plugin-strip doesn't strip console.logs properly

function log () {
}

// Check which emojis we know for sure aren't supported, based on Unicode version level
const emojiSupportLevelPromise = new Promise(resolve => (
  rIC(() => (
    resolve(determineEmojiSupportLevel()) // delay so ideally this can run while IDB is first populating
  ))
));
// determine which emojis containing ZWJ (zero width joiner) characters
// are supported (rendered as one glyph) rather than unsupported (rendered as two or more glyphs)
const supportedZwjEmojis = new Map();

const VARIATION_SELECTOR = '\ufe0f';
const SKINTONE_MODIFIER = '\ud83c';
const ZWJ = '\u200d';
const LIGHT_SKIN_TONE = 0x1F3FB;
const LIGHT_SKIN_TONE_MODIFIER = 0xdffb;

// TODO: this is a naive implementation, we can improve it later
// It's only used for the skintone picker, so as long as people don't customize with
// really exotic emoji then it should work fine
function applySkinTone (str, skinTone) {
  if (skinTone === 0) {
    return str
  }
  const zwjIndex = str.indexOf(ZWJ);
  if (zwjIndex !== -1) {
    return str.substring(0, zwjIndex) +
      String.fromCodePoint(LIGHT_SKIN_TONE + skinTone - 1) +
      str.substring(zwjIndex)
  }
  if (str.endsWith(VARIATION_SELECTOR)) {
    str = str.substring(0, str.length - 1);
  }
  return str + SKINTONE_MODIFIER + String.fromCodePoint(LIGHT_SKIN_TONE_MODIFIER + skinTone - 1)
}

function halt (event) {
  event.preventDefault();
  event.stopPropagation();
}

// Implementation left/right or up/down navigation, circling back when you
// reach the start/end of the list
function incrementOrDecrement (decrement, val, arr) {
  val += (decrement ? -1 : 1);
  if (val < 0) {
    val = arr.length - 1;
  } else if (val >= arr.length) {
    val = 0;
  }
  return val
}

// like lodash's uniqBy but much smaller
function uniqBy (arr, func) {
  const set = new Set();
  const res = [];
  for (const item of arr) {
    const key = func(item);
    if (!set.has(key)) {
      set.add(key);
      res.push(item);
    }
  }
  return res
}

// We don't need all the data on every emoji, and there are specific things we need
// for the UI, so build a "view model" from the emoji object we got from the database

function summarizeEmojisForUI (emojis, emojiSupportLevel) {
  const toSimpleSkinsMap = skins => {
    const res = {};
    for (const skin of skins) {
      // ignore arrays like [1, 2] with multiple skin tones
      // also ignore variants that are in an unsupported emoji version
      // (these do exist - variants from a different version than their base emoji)
      if (typeof skin.tone === 'number' && skin.version <= emojiSupportLevel) {
        res[skin.tone] = skin.unicode;
      }
    }
    return res
  };

  return emojis.map(({ unicode, skins, shortcodes, url, name, category }) => ({
    unicode,
    name,
    shortcodes,
    url,
    category,
    id: unicode || name,
    skins: skins && toSimpleSkinsMap(skins),
    title: (shortcodes || []).join(', ')
  }))
}

// import rAF from one place so that the bundle size is a bit smaller
const rAF = requestAnimationFrame;

// Svelte action to calculate the width of an element and auto-update

const resizeObserverSupported = typeof ResizeObserver === 'function';

function calculateWidth (node, onUpdate) {
  let resizeObserver;
  /* istanbul ignore if */
  if (resizeObserverSupported) {
    resizeObserver = new ResizeObserver(entries => (
      onUpdate(entries[0].contentRect.width)
    ));
    resizeObserver.observe(node);
  } else { // just set the width once, don't bother trying to track it
    rAF(() => (
      onUpdate(node.getBoundingClientRect().width)
    ));
  }

  return {
    destroy () {
      /* istanbul ignore if */
      if (resizeObserver) {
        resizeObserver.disconnect();
      }
    }
  }
}

// get the width of the text inside of a DOM node, via https://stackoverflow.com/a/59525891/680742
function calculateTextWidth (node) {
  /* istanbul ignore else */
  {
    const range = document.createRange();
    range.selectNode(node.firstChild);
    return range.getBoundingClientRect().width
  }
}

let baselineEmojiWidth;

function checkZwjSupport (zwjEmojisToCheck, baselineEmoji, emojiToDomNode) {
  for (const emoji of zwjEmojisToCheck) {
    const domNode = emojiToDomNode(emoji);
    const emojiWidth = calculateTextWidth(domNode);
    if (typeof baselineEmojiWidth === 'undefined') { // calculate the baseline emoji width only once
      baselineEmojiWidth = calculateTextWidth(baselineEmoji);
    }
    // On Windows, some supported emoji are ~50% bigger than the baseline emoji, but what we really want to guard
    // against are the ones that are 2x the size, because those are truly broken (person with red hair = person with
    // floating red wig, black cat = cat with black square, polar bear = bear with snowflake, etc.)
    // So here we set the threshold at 1.8 times the size of the baseline emoji.
    const supported = emojiWidth / 1.8 < baselineEmojiWidth;
    supportedZwjEmojis.set(emoji.unicode, supported);
    /* istanbul ignore next */
    if (!supported) {
      log('Filtered unsupported emoji', emoji.unicode);
    } else if (emojiWidth !== baselineEmojiWidth) {
      log('Allowed borderline emoji', emoji.unicode);
    }
  }
}

// Measure after style/layout are complete

const requestPostAnimationFrame = callback => {
  rAF(() => {
    setTimeout(callback);
  });
};

// like lodash's uniq

function uniq (arr) {
  return uniqBy(arr, _ => _)
}

/* src/picker/components/Picker/Picker.svelte generated by Svelte v3.29.4 */

const { Map: Map_1 } = globals;

function get_each_context(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[65] = list[i];
	child_ctx[67] = i;
	return child_ctx;
}

function get_each_context_2(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[65] = list[i];
	child_ctx[67] = i;
	return child_ctx;
}

function get_each_context_1(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[68] = list[i];
	child_ctx[67] = i;
	return child_ctx;
}

function get_each_context_3(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[71] = list[i];
	return child_ctx;
}

function get_each_context_4(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[74] = list[i];
	child_ctx[67] = i;
	return child_ctx;
}

// (44:38) {#each skinTones as skinTone, i (skinTone)}
function create_each_block_4(key_1, ctx) {
	let div;
	let t_value = /*skinTone*/ ctx[74] + "";
	let t;
	let div_id_value;
	let div_class_value;
	let div_aria_selected_value;
	let div_title_value;
	let div_aria_label_value;

	return {
		key: key_1,
		first: null,
		c() {
			div = element("div");
			t = text(t_value);
			attr(div, "id", div_id_value = "skintone-" + /*i*/ ctx[67]);

			attr(div, "class", div_class_value = "emoji skintone-option cursor-pointer hide-focus " + (/*i*/ ctx[67] === /*activeSkinTone*/ ctx[15]
			? "active"
			: ""));

			attr(div, "aria-selected", div_aria_selected_value = /*i*/ ctx[67] === /*activeSkinTone*/ ctx[15]);
			attr(div, "role", "option");
			attr(div, "title", div_title_value = /*i18n*/ ctx[0].skinTones[/*i*/ ctx[67]]);
			attr(div, "tabindex", "-1");
			attr(div, "aria-label", div_aria_label_value = /*i18n*/ ctx[0].skinTones[/*i*/ ctx[67]]);
			this.first = div;
		},
		m(target, anchor) {
			insert(target, div, anchor);
			append(div, t);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*skinTones*/ 524288 && t_value !== (t_value = /*skinTone*/ ctx[74] + "")) set_data(t, t_value);

			if (dirty[0] & /*skinTones*/ 524288 && div_id_value !== (div_id_value = "skintone-" + /*i*/ ctx[67])) {
				attr(div, "id", div_id_value);
			}

			if (dirty[0] & /*skinTones, activeSkinTone*/ 557056 && div_class_value !== (div_class_value = "emoji skintone-option cursor-pointer hide-focus " + (/*i*/ ctx[67] === /*activeSkinTone*/ ctx[15]
			? "active"
			: ""))) {
				attr(div, "class", div_class_value);
			}

			if (dirty[0] & /*skinTones, activeSkinTone*/ 557056 && div_aria_selected_value !== (div_aria_selected_value = /*i*/ ctx[67] === /*activeSkinTone*/ ctx[15])) {
				attr(div, "aria-selected", div_aria_selected_value);
			}

			if (dirty[0] & /*i18n, skinTones*/ 524289 && div_title_value !== (div_title_value = /*i18n*/ ctx[0].skinTones[/*i*/ ctx[67]])) {
				attr(div, "title", div_title_value);
			}

			if (dirty[0] & /*i18n, skinTones*/ 524289 && div_aria_label_value !== (div_aria_label_value = /*i18n*/ ctx[0].skinTones[/*i*/ ctx[67]])) {
				attr(div, "aria-label", div_aria_label_value);
			}
		},
		d(detaching) {
			if (detaching) detach(div);
		}
	};
}

// (54:33) {#each groups as group (group.id)}
function create_each_block_3(key_1, ctx) {
	let button;
	let div;
	let t_value = /*group*/ ctx[71].emoji + "";
	let t;
	let button_aria_controls_value;
	let button_aria_label_value;
	let button_aria_selected_value;
	let button_title_value;
	let mounted;
	let dispose;

	function click_handler(...args) {
		return /*click_handler*/ ctx[46](/*group*/ ctx[71], ...args);
	}

	return {
		key: key_1,
		first: null,
		c() {
			button = element("button");
			div = element("div");
			t = text(t_value);
			attr(div, "class", "emoji");
			attr(button, "role", "tab");
			attr(button, "class", "nav-button");
			attr(button, "aria-controls", button_aria_controls_value = "tab-" + /*group*/ ctx[71].id);
			attr(button, "aria-label", button_aria_label_value = /*i18n*/ ctx[0].categories[/*group*/ ctx[71].name]);
			attr(button, "aria-selected", button_aria_selected_value = !/*searchMode*/ ctx[7] && /*currentGroup*/ ctx[23].id === /*group*/ ctx[71].id);
			attr(button, "title", button_title_value = /*i18n*/ ctx[0].categories[/*group*/ ctx[71].name]);
			this.first = button;
		},
		m(target, anchor) {
			insert(target, button, anchor);
			append(button, div);
			append(div, t);

			if (!mounted) {
				dispose = listen(button, "click", click_handler);
				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;
			if (dirty[0] & /*groups*/ 4194304 && t_value !== (t_value = /*group*/ ctx[71].emoji + "")) set_data(t, t_value);

			if (dirty[0] & /*groups*/ 4194304 && button_aria_controls_value !== (button_aria_controls_value = "tab-" + /*group*/ ctx[71].id)) {
				attr(button, "aria-controls", button_aria_controls_value);
			}

			if (dirty[0] & /*i18n, groups*/ 4194305 && button_aria_label_value !== (button_aria_label_value = /*i18n*/ ctx[0].categories[/*group*/ ctx[71].name])) {
				attr(button, "aria-label", button_aria_label_value);
			}

			if (dirty[0] & /*searchMode, currentGroup, groups*/ 12583040 && button_aria_selected_value !== (button_aria_selected_value = !/*searchMode*/ ctx[7] && /*currentGroup*/ ctx[23].id === /*group*/ ctx[71].id)) {
				attr(button, "aria-selected", button_aria_selected_value);
			}

			if (dirty[0] & /*i18n, groups*/ 4194305 && button_title_value !== (button_title_value = /*i18n*/ ctx[0].categories[/*group*/ ctx[71].name])) {
				attr(button, "title", button_title_value);
			}
		},
		d(detaching) {
			if (detaching) detach(button);
			mounted = false;
			dispose();
		}
	};
}

// (89:98) {:else}
function create_else_block_1(ctx) {
	let img;
	let img_src_value;

	return {
		c() {
			img = element("img");
			attr(img, "class", "custom-emoji");
			if (img.src !== (img_src_value = /*emoji*/ ctx[65].url)) attr(img, "src", img_src_value);
			attr(img, "alt", "");
			attr(img, "loading", "lazy");
		},
		m(target, anchor) {
			insert(target, img, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && img.src !== (img_src_value = /*emoji*/ ctx[65].url)) {
				attr(img, "src", img_src_value);
			}
		},
		d(detaching) {
			if (detaching) detach(img);
		}
	};
}

// (89:38) {#if emoji.unicode}
function create_if_block_1(ctx) {
	let t_value = unicodeWithSkin(/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]) + "";
	let t;

	return {
		c() {
			t = text(t_value);
		},
		m(target, anchor) {
			insert(target, t, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*currentEmojisWithCategories, currentSkinTone*/ 16388 && t_value !== (t_value = unicodeWithSkin(/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]) + "")) set_data(t, t_value);
		},
		d(detaching) {
			if (detaching) detach(t);
		}
	};
}

// (84:39) {#each emojiWithCategory.emojis as emoji, i (emoji.id)}
function create_each_block_2(key_1, ctx) {
	let button;
	let button_role_value;
	let button_aria_selected_value;
	let button_aria_label_value;
	let button_title_value;
	let button_class_value;
	let button_id_value;

	function select_block_type(ctx, dirty) {
		if (/*emoji*/ ctx[65].unicode) return create_if_block_1;
		return create_else_block_1;
	}

	let current_block_type = select_block_type(ctx);
	let if_block = current_block_type(ctx);

	return {
		key: key_1,
		first: null,
		c() {
			button = element("button");
			if_block.c();
			attr(button, "role", button_role_value = /*searchMode*/ ctx[7] ? "option" : "menuitem");

			attr(button, "aria-selected", button_aria_selected_value = /*searchMode*/ ctx[7]
			? /*i*/ ctx[67] == /*activeSearchItem*/ ctx[8]
			: "");

			attr(button, "aria-label", button_aria_label_value = /*labelWithSkin*/ ctx[26](/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]));
			attr(button, "title", button_title_value = /*emoji*/ ctx[65].title);

			attr(button, "class", button_class_value = "emoji " + (/*searchMode*/ ctx[7] && /*i*/ ctx[67] === /*activeSearchItem*/ ctx[8]
			? "active"
			: ""));

			attr(button, "id", button_id_value = "emo-" + /*emoji*/ ctx[65].id);
			this.first = button;
		},
		m(target, anchor) {
			insert(target, button, anchor);
			if_block.m(button, null);
		},
		p(ctx, dirty) {
			if (current_block_type === (current_block_type = select_block_type(ctx)) && if_block) {
				if_block.p(ctx, dirty);
			} else {
				if_block.d(1);
				if_block = current_block_type(ctx);

				if (if_block) {
					if_block.c();
					if_block.m(button, null);
				}
			}

			if (dirty[0] & /*searchMode*/ 128 && button_role_value !== (button_role_value = /*searchMode*/ ctx[7] ? "option" : "menuitem")) {
				attr(button, "role", button_role_value);
			}

			if (dirty[0] & /*searchMode, currentEmojisWithCategories, activeSearchItem*/ 388 && button_aria_selected_value !== (button_aria_selected_value = /*searchMode*/ ctx[7]
			? /*i*/ ctx[67] == /*activeSearchItem*/ ctx[8]
			: "")) {
				attr(button, "aria-selected", button_aria_selected_value);
			}

			if (dirty[0] & /*currentEmojisWithCategories, currentSkinTone*/ 16388 && button_aria_label_value !== (button_aria_label_value = /*labelWithSkin*/ ctx[26](/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]))) {
				attr(button, "aria-label", button_aria_label_value);
			}

			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && button_title_value !== (button_title_value = /*emoji*/ ctx[65].title)) {
				attr(button, "title", button_title_value);
			}

			if (dirty[0] & /*searchMode, currentEmojisWithCategories, activeSearchItem*/ 388 && button_class_value !== (button_class_value = "emoji " + (/*searchMode*/ ctx[7] && /*i*/ ctx[67] === /*activeSearchItem*/ ctx[8]
			? "active"
			: ""))) {
				attr(button, "class", button_class_value);
			}

			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && button_id_value !== (button_id_value = "emo-" + /*emoji*/ ctx[65].id)) {
				attr(button, "id", button_id_value);
			}
		},
		d(detaching) {
			if (detaching) detach(button);
			if_block.d();
		}
	};
}

// (71:3) {#each currentEmojisWithCategories as emojiWithCategory, i (emojiWithCategory.category)}
function create_each_block_1(key_1, ctx) {
	let div0;

	let t_value = (/*searchMode*/ ctx[7]
	? /*i18n*/ ctx[0].searchResultsLabel
	: /*emojiWithCategory*/ ctx[68].category
		? /*emojiWithCategory*/ ctx[68].category
		: /*currentEmojisWithCategories*/ ctx[2].length > 1
			? /*i18n*/ ctx[0].categories.custom
			: /*i18n*/ ctx[0].categories[/*currentGroup*/ ctx[23].name]) + "";

	let t;
	let div0_id_value;
	let div0_class_value;
	let div1;
	let each_blocks = [];
	let each_1_lookup = new Map_1();
	let div1_role_value;
	let div1_aria_labelledby_value;
	let div1_id_value;
	let calculateEmojiGridWidth_action;
	let mounted;
	let dispose;
	let each_value_2 = /*emojiWithCategory*/ ctx[68].emojis;
	const get_key = ctx => /*emoji*/ ctx[65].id;

	for (let i = 0; i < each_value_2.length; i += 1) {
		let child_ctx = get_each_context_2(ctx, each_value_2, i);
		let key = get_key(child_ctx);
		each_1_lookup.set(key, each_blocks[i] = create_each_block_2(key, child_ctx));
	}

	return {
		key: key_1,
		first: null,
		c() {
			div0 = element("div");
			t = text(t_value);
			div1 = element("div");

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			attr(div0, "id", div0_id_value = "menu-label-" + /*i*/ ctx[67]);

			attr(div0, "class", div0_class_value = "category " + (/*currentEmojisWithCategories*/ ctx[2].length > 1
			? ""
			: "gone"));

			attr(div0, "aria-hidden", "true");
			attr(div1, "class", "emoji-menu");
			attr(div1, "role", div1_role_value = /*searchMode*/ ctx[7] ? "listbox" : "menu");
			attr(div1, "aria-labelledby", div1_aria_labelledby_value = "menu-label-" + /*i*/ ctx[67]);
			attr(div1, "id", div1_id_value = /*searchMode*/ ctx[7] ? "search-results" : "");
			this.first = div0;
		},
		m(target, anchor) {
			insert(target, div0, anchor);
			append(div0, t);
			insert(target, div1, anchor);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(div1, null);
			}

			if (!mounted) {
				dispose = action_destroyer(calculateEmojiGridWidth_action = /*calculateEmojiGridWidth*/ ctx[27].call(null, div1));
				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (dirty[0] & /*searchMode, i18n, currentEmojisWithCategories, currentGroup*/ 8388741 && t_value !== (t_value = (/*searchMode*/ ctx[7]
			? /*i18n*/ ctx[0].searchResultsLabel
			: /*emojiWithCategory*/ ctx[68].category
				? /*emojiWithCategory*/ ctx[68].category
				: /*currentEmojisWithCategories*/ ctx[2].length > 1
					? /*i18n*/ ctx[0].categories.custom
					: /*i18n*/ ctx[0].categories[/*currentGroup*/ ctx[23].name]) + "")) set_data(t, t_value);

			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && div0_id_value !== (div0_id_value = "menu-label-" + /*i*/ ctx[67])) {
				attr(div0, "id", div0_id_value);
			}

			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && div0_class_value !== (div0_class_value = "category " + (/*currentEmojisWithCategories*/ ctx[2].length > 1
			? ""
			: "gone"))) {
				attr(div0, "class", div0_class_value);
			}

			if (dirty[0] & /*searchMode, currentEmojisWithCategories, activeSearchItem, labelWithSkin, currentSkinTone*/ 67125636) {
				const each_value_2 = /*emojiWithCategory*/ ctx[68].emojis;
				each_blocks = update_keyed_each(each_blocks, dirty, get_key, 1, ctx, each_value_2, each_1_lookup, div1, destroy_block, create_each_block_2, null, get_each_context_2);
			}

			if (dirty[0] & /*searchMode*/ 128 && div1_role_value !== (div1_role_value = /*searchMode*/ ctx[7] ? "listbox" : "menu")) {
				attr(div1, "role", div1_role_value);
			}

			if (dirty[0] & /*currentEmojisWithCategories*/ 4 && div1_aria_labelledby_value !== (div1_aria_labelledby_value = "menu-label-" + /*i*/ ctx[67])) {
				attr(div1, "aria-labelledby", div1_aria_labelledby_value);
			}

			if (dirty[0] & /*searchMode*/ 128 && div1_id_value !== (div1_id_value = /*searchMode*/ ctx[7] ? "search-results" : "")) {
				attr(div1, "id", div1_id_value);
			}
		},
		d(detaching) {
			if (detaching) detach(div0);
			if (detaching) detach(div1);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].d();
			}

			mounted = false;
			dispose();
		}
	};
}

// (101:94) {:else}
function create_else_block(ctx) {
	let img;
	let img_src_value;

	return {
		c() {
			img = element("img");
			attr(img, "class", "custom-emoji");
			if (img.src !== (img_src_value = /*emoji*/ ctx[65].url)) attr(img, "src", img_src_value);
			attr(img, "alt", "");
			attr(img, "loading", "lazy");
		},
		m(target, anchor) {
			insert(target, img, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*currentFavorites*/ 1048576 && img.src !== (img_src_value = /*emoji*/ ctx[65].url)) {
				attr(img, "src", img_src_value);
			}
		},
		d(detaching) {
			if (detaching) detach(img);
		}
	};
}

// (101:34) {#if emoji.unicode}
function create_if_block(ctx) {
	let t_value = unicodeWithSkin(/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]) + "";
	let t;

	return {
		c() {
			t = text(t_value);
		},
		m(target, anchor) {
			insert(target, t, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*currentFavorites, currentSkinTone*/ 1064960 && t_value !== (t_value = unicodeWithSkin(/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]) + "")) set_data(t, t_value);
		},
		d(detaching) {
			if (detaching) detach(t);
		}
	};
}

// (97:62) {#each currentFavorites as emoji, i (emoji.id)}
function create_each_block(key_1, ctx) {
	let button;
	let button_aria_label_value;
	let button_title_value;
	let button_id_value;

	function select_block_type_1(ctx, dirty) {
		if (/*emoji*/ ctx[65].unicode) return create_if_block;
		return create_else_block;
	}

	let current_block_type = select_block_type_1(ctx);
	let if_block = current_block_type(ctx);

	return {
		key: key_1,
		first: null,
		c() {
			button = element("button");
			if_block.c();
			attr(button, "role", "menuitem");
			attr(button, "aria-label", button_aria_label_value = /*labelWithSkin*/ ctx[26](/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]));
			attr(button, "title", button_title_value = /*emoji*/ ctx[65].title);
			attr(button, "class", "emoji");
			attr(button, "id", button_id_value = "fav-" + /*emoji*/ ctx[65].id);
			this.first = button;
		},
		m(target, anchor) {
			insert(target, button, anchor);
			if_block.m(button, null);
		},
		p(ctx, dirty) {
			if (current_block_type === (current_block_type = select_block_type_1(ctx)) && if_block) {
				if_block.p(ctx, dirty);
			} else {
				if_block.d(1);
				if_block = current_block_type(ctx);

				if (if_block) {
					if_block.c();
					if_block.m(button, null);
				}
			}

			if (dirty[0] & /*currentFavorites, currentSkinTone*/ 1064960 && button_aria_label_value !== (button_aria_label_value = /*labelWithSkin*/ ctx[26](/*emoji*/ ctx[65], /*currentSkinTone*/ ctx[14]))) {
				attr(button, "aria-label", button_aria_label_value);
			}

			if (dirty[0] & /*currentFavorites*/ 1048576 && button_title_value !== (button_title_value = /*emoji*/ ctx[65].title)) {
				attr(button, "title", button_title_value);
			}

			if (dirty[0] & /*currentFavorites*/ 1048576 && button_id_value !== (button_id_value = "fav-" + /*emoji*/ ctx[65].id)) {
				attr(button, "id", button_id_value);
			}
		},
		d(detaching) {
			if (detaching) detach(button);
			if_block.d();
		}
	};
}

function create_fragment(ctx) {
	let section;
	let div0;
	let div4;
	let div1;
	let input;
	let input_placeholder_value;
	let input_aria_expanded_value;
	let input_aria_activedescendant_value;
	let label;
	let t0_value = /*i18n*/ ctx[0].searchLabel + "";
	let t0;
	let span0;
	let t1_value = /*i18n*/ ctx[0].searchDescription + "";
	let t1;
	let div2;
	let button0;
	let t2;
	let button0_class_value;
	let div2_class_value;
	let span1;
	let t3_value = /*i18n*/ ctx[0].skinToneDescription + "";
	let t3;
	let div3;
	let each_blocks_3 = [];
	let each0_lookup = new Map_1();
	let div3_class_value;
	let div3_style_value;
	let div3_aria_label_value;
	let div3_aria_activedescendant_value;
	let div3_aria_hidden_value;
	let div5;
	let each_blocks_2 = [];
	let each1_lookup = new Map_1();
	let div5_aria_label_value;
	let div7;
	let div6;
	let calculateIndicatorWidth_action;
	let div8;
	let t4;
	let div8_class_value;
	let div9;
	let each_blocks_1 = [];
	let each2_lookup = new Map_1();
	let div9_class_value;
	let div9_role_value;
	let div9_aria_label_value;
	let div9_id_value;
	let div10;
	let each_blocks = [];
	let each3_lookup = new Map_1();
	let div10_class_value;
	let div10_aria_label_value;
	let div11;
	let button1;
	let section_aria_label_value;
	let mounted;
	let dispose;
	let each_value_4 = /*skinTones*/ ctx[19];
	const get_key = ctx => /*skinTone*/ ctx[74];

	for (let i = 0; i < each_value_4.length; i += 1) {
		let child_ctx = get_each_context_4(ctx, each_value_4, i);
		let key = get_key(child_ctx);
		each0_lookup.set(key, each_blocks_3[i] = create_each_block_4(key, child_ctx));
	}

	let each_value_3 = /*groups*/ ctx[22];
	const get_key_1 = ctx => /*group*/ ctx[71].id;

	for (let i = 0; i < each_value_3.length; i += 1) {
		let child_ctx = get_each_context_3(ctx, each_value_3, i);
		let key = get_key_1(child_ctx);
		each1_lookup.set(key, each_blocks_2[i] = create_each_block_3(key, child_ctx));
	}

	let each_value_1 = /*currentEmojisWithCategories*/ ctx[2];
	const get_key_2 = ctx => /*emojiWithCategory*/ ctx[68].category;

	for (let i = 0; i < each_value_1.length; i += 1) {
		let child_ctx = get_each_context_1(ctx, each_value_1, i);
		let key = get_key_2(child_ctx);
		each2_lookup.set(key, each_blocks_1[i] = create_each_block_1(key, child_ctx));
	}

	let each_value = /*currentFavorites*/ ctx[20];
	const get_key_3 = ctx => /*emoji*/ ctx[65].id;

	for (let i = 0; i < each_value.length; i += 1) {
		let child_ctx = get_each_context(ctx, each_value, i);
		let key = get_key_3(child_ctx);
		each3_lookup.set(key, each_blocks[i] = create_each_block(key, child_ctx));
	}

	return {
		c() {
			section = element("section");
			div0 = element("div");
			div4 = element("div");
			div1 = element("div");
			input = element("input");
			label = element("label");
			t0 = text(t0_value);
			span0 = element("span");
			t1 = text(t1_value);
			div2 = element("div");
			button0 = element("button");
			t2 = text(/*skinToneButtonText*/ ctx[16]);
			span1 = element("span");
			t3 = text(t3_value);
			div3 = element("div");

			for (let i = 0; i < each_blocks_3.length; i += 1) {
				each_blocks_3[i].c();
			}

			div5 = element("div");

			for (let i = 0; i < each_blocks_2.length; i += 1) {
				each_blocks_2[i].c();
			}

			div7 = element("div");
			div6 = element("div");
			div8 = element("div");
			t4 = text(/*message*/ ctx[9]);
			div9 = element("div");

			for (let i = 0; i < each_blocks_1.length; i += 1) {
				each_blocks_1[i].c();
			}

			div10 = element("div");

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			div11 = element("div");
			button1 = element("button");
			button1.textContent = "😀";
			this.c = noop;
			attr(div0, "class", "pad-top");
			attr(input, "id", "search");
			attr(input, "class", "search");
			attr(input, "type", "search");
			attr(input, "role", "combobox");
			attr(input, "enterkeyhint", "search");
			attr(input, "placeholder", input_placeholder_value = /*i18n*/ ctx[0].searchLabel);
			attr(input, "autocapitalize", "none");
			attr(input, "autocomplete", "off");
			attr(input, "spellcheck", "true");
			attr(input, "aria-expanded", input_aria_expanded_value = !!(/*searchMode*/ ctx[7] && /*currentEmojis*/ ctx[1].length));
			attr(input, "aria-controls", "search-results");
			attr(input, "aria-owns", "search-results");
			attr(input, "aria-describedby", "search-description");
			attr(input, "aria-autocomplete", "list");

			attr(input, "aria-activedescendant", input_aria_activedescendant_value = /*activeSearchItemId*/ ctx[25]
			? `emo-${/*activeSearchItemId*/ ctx[25]}`
			: "");

			attr(label, "class", "sr-only");
			attr(label, "for", "search");
			attr(span0, "id", "search-description");
			attr(span0, "class", "sr-only");
			attr(div1, "class", "search-wrapper");
			attr(button0, "id", "skintone-button");
			attr(button0, "class", button0_class_value = "emoji " + (/*skinTonePickerExpanded*/ ctx[11] ? "hide-focus" : ""));
			attr(button0, "aria-label", /*skinToneButtonLabel*/ ctx[18]);
			attr(button0, "title", /*skinToneButtonLabel*/ ctx[18]);
			attr(button0, "aria-describedby", "skintone-description");
			attr(button0, "aria-haspopup", "listbox");
			attr(button0, "aria-expanded", /*skinTonePickerExpanded*/ ctx[11]);
			attr(button0, "aria-controls", "skintone-list");

			attr(div2, "class", div2_class_value = "skintone-button-wrapper " + (/*skinTonePickerExpandedAfterAnimation*/ ctx[12]
			? "expanded"
			: ""));

			attr(span1, "id", "skintone-description");
			attr(span1, "class", "sr-only");
			attr(div3, "id", "skintone-list");

			attr(div3, "class", div3_class_value = "skintone-list " + (/*skinTonePickerExpanded*/ ctx[11]
			? ""
			: "hidden no-animate"));

			attr(div3, "style", div3_style_value = /*skinTonePickerExpanded*/ ctx[11]
			? "transform: translateY(0);"
			: "transform: translateY(calc(-1 * var(--num-skintones) * var(--total-emoji-size)))");

			attr(div3, "role", "listbox");
			attr(div3, "aria-label", div3_aria_label_value = /*i18n*/ ctx[0].skinTonesLabel);
			attr(div3, "aria-activedescendant", div3_aria_activedescendant_value = "skintone-" + /*activeSkinTone*/ ctx[15]);
			attr(div3, "aria-hidden", div3_aria_hidden_value = !/*skinTonePickerExpanded*/ ctx[11]);
			attr(div4, "class", "search-row");
			attr(div5, "class", "nav");
			attr(div5, "role", "tablist");
			set_style(div5, "grid-template-columns", "repeat(" + /*groups*/ ctx[22].length + ", 1fr)");
			attr(div5, "aria-label", div5_aria_label_value = /*i18n*/ ctx[0].categoriesLabel);
			attr(div6, "class", "indicator");
			attr(div6, "style", /*indicatorStyle*/ ctx[10]);
			attr(div7, "class", "indicator-wrapper");
			attr(div8, "class", div8_class_value = "message " + (/*message*/ ctx[9] ? "" : "gone"));
			attr(div8, "role", "alert");
			attr(div8, "aria-live", "polite");
			attr(div9, "class", div9_class_value = "tabpanel " + (!/*loaded*/ ctx[24] || /*message*/ ctx[9] ? "gone" : ""));
			attr(div9, "role", div9_role_value = /*searchMode*/ ctx[7] ? "region" : "tabpanel");

			attr(div9, "aria-label", div9_aria_label_value = /*searchMode*/ ctx[7]
			? /*i18n*/ ctx[0].searchResultsLabel
			: /*i18n*/ ctx[0].categories[/*currentGroup*/ ctx[23].name]);

			attr(div9, "id", div9_id_value = /*searchMode*/ ctx[7]
			? ""
			: `tab-${/*currentGroup*/ ctx[23].id}`);

			attr(div9, "tabindex", "0");
			attr(div10, "class", div10_class_value = "favorites emoji-menu " + (/*message*/ ctx[9] ? "gone" : ""));
			attr(div10, "role", "menu");
			attr(div10, "aria-label", div10_aria_label_value = /*i18n*/ ctx[0].favoritesLabel);
			set_style(div10, "padding-right", /*scrollbarWidth*/ ctx[21] + "px");
			attr(button1, "tabindex", "-1");
			attr(button1, "class", "emoji baseline-emoji");
			attr(div11, "aria-hidden", "true");
			attr(div11, "class", "hidden abs-pos");
			attr(section, "class", "picker");
			attr(section, "aria-label", section_aria_label_value = /*i18n*/ ctx[0].regionLabel);
			attr(section, "style", /*pickerStyle*/ ctx[17]);
		},
		m(target, anchor) {
			insert(target, section, anchor);
			append(section, div0);
			append(section, div4);
			append(div4, div1);
			append(div1, input);
			set_input_value(input, /*rawSearchText*/ ctx[3]);
			append(div1, label);
			append(label, t0);
			append(div1, span0);
			append(span0, t1);
			append(div4, div2);
			append(div2, button0);
			append(button0, t2);
			append(div4, span1);
			append(span1, t3);
			append(div4, div3);

			for (let i = 0; i < each_blocks_3.length; i += 1) {
				each_blocks_3[i].m(div3, null);
			}

			/*div3_binding*/ ctx[45](div3);
			append(section, div5);

			for (let i = 0; i < each_blocks_2.length; i += 1) {
				each_blocks_2[i].m(div5, null);
			}

			append(section, div7);
			append(div7, div6);
			append(section, div8);
			append(div8, t4);
			append(section, div9);

			for (let i = 0; i < each_blocks_1.length; i += 1) {
				each_blocks_1[i].m(div9, null);
			}

			/*div9_binding*/ ctx[47](div9);
			append(section, div10);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(div10, null);
			}

			append(section, div11);
			append(div11, button1);
			/*button1_binding*/ ctx[48](button1);
			/*section_binding*/ ctx[49](section);

			if (!mounted) {
				dispose = [
					listen(input, "input", /*input_input_handler*/ ctx[44]),
					listen(input, "keydown", /*onSearchKeydown*/ ctx[29]),
					listen(button0, "click", /*onClickSkinToneButton*/ ctx[34]),
					listen(div3, "focusout", /*onSkinToneOptionsFocusOut*/ ctx[37]),
					listen(div3, "click", /*onSkinToneOptionsClick*/ ctx[33]),
					listen(div3, "keydown", /*onSkinToneOptionsKeydown*/ ctx[35]),
					listen(div3, "keyup", /*onSkinToneOptionsKeyup*/ ctx[36]),
					listen(div5, "keydown", /*onNavKeydown*/ ctx[31]),
					action_destroyer(calculateIndicatorWidth_action = /*calculateIndicatorWidth*/ ctx[28].call(null, div6)),
					listen(div9, "click", /*onEmojiClick*/ ctx[32]),
					listen(div10, "click", /*onEmojiClick*/ ctx[32])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (dirty[0] & /*i18n*/ 1 && input_placeholder_value !== (input_placeholder_value = /*i18n*/ ctx[0].searchLabel)) {
				attr(input, "placeholder", input_placeholder_value);
			}

			if (dirty[0] & /*searchMode, currentEmojis*/ 130 && input_aria_expanded_value !== (input_aria_expanded_value = !!(/*searchMode*/ ctx[7] && /*currentEmojis*/ ctx[1].length))) {
				attr(input, "aria-expanded", input_aria_expanded_value);
			}

			if (dirty[0] & /*activeSearchItemId*/ 33554432 && input_aria_activedescendant_value !== (input_aria_activedescendant_value = /*activeSearchItemId*/ ctx[25]
			? `emo-${/*activeSearchItemId*/ ctx[25]}`
			: "")) {
				attr(input, "aria-activedescendant", input_aria_activedescendant_value);
			}

			if (dirty[0] & /*rawSearchText*/ 8) {
				set_input_value(input, /*rawSearchText*/ ctx[3]);
			}

			if (dirty[0] & /*i18n*/ 1 && t0_value !== (t0_value = /*i18n*/ ctx[0].searchLabel + "")) set_data(t0, t0_value);
			if (dirty[0] & /*i18n*/ 1 && t1_value !== (t1_value = /*i18n*/ ctx[0].searchDescription + "")) set_data(t1, t1_value);
			if (dirty[0] & /*skinToneButtonText*/ 65536) set_data(t2, /*skinToneButtonText*/ ctx[16]);

			if (dirty[0] & /*skinTonePickerExpanded*/ 2048 && button0_class_value !== (button0_class_value = "emoji " + (/*skinTonePickerExpanded*/ ctx[11] ? "hide-focus" : ""))) {
				attr(button0, "class", button0_class_value);
			}

			if (dirty[0] & /*skinToneButtonLabel*/ 262144) {
				attr(button0, "aria-label", /*skinToneButtonLabel*/ ctx[18]);
			}

			if (dirty[0] & /*skinToneButtonLabel*/ 262144) {
				attr(button0, "title", /*skinToneButtonLabel*/ ctx[18]);
			}

			if (dirty[0] & /*skinTonePickerExpanded*/ 2048) {
				attr(button0, "aria-expanded", /*skinTonePickerExpanded*/ ctx[11]);
			}

			if (dirty[0] & /*skinTonePickerExpandedAfterAnimation*/ 4096 && div2_class_value !== (div2_class_value = "skintone-button-wrapper " + (/*skinTonePickerExpandedAfterAnimation*/ ctx[12]
			? "expanded"
			: ""))) {
				attr(div2, "class", div2_class_value);
			}

			if (dirty[0] & /*i18n*/ 1 && t3_value !== (t3_value = /*i18n*/ ctx[0].skinToneDescription + "")) set_data(t3, t3_value);

			if (dirty[0] & /*skinTones, activeSkinTone, i18n*/ 557057) {
				const each_value_4 = /*skinTones*/ ctx[19];
				each_blocks_3 = update_keyed_each(each_blocks_3, dirty, get_key, 1, ctx, each_value_4, each0_lookup, div3, destroy_block, create_each_block_4, null, get_each_context_4);
			}

			if (dirty[0] & /*skinTonePickerExpanded*/ 2048 && div3_class_value !== (div3_class_value = "skintone-list " + (/*skinTonePickerExpanded*/ ctx[11]
			? ""
			: "hidden no-animate"))) {
				attr(div3, "class", div3_class_value);
			}

			if (dirty[0] & /*skinTonePickerExpanded*/ 2048 && div3_style_value !== (div3_style_value = /*skinTonePickerExpanded*/ ctx[11]
			? "transform: translateY(0);"
			: "transform: translateY(calc(-1 * var(--num-skintones) * var(--total-emoji-size)))")) {
				attr(div3, "style", div3_style_value);
			}

			if (dirty[0] & /*i18n*/ 1 && div3_aria_label_value !== (div3_aria_label_value = /*i18n*/ ctx[0].skinTonesLabel)) {
				attr(div3, "aria-label", div3_aria_label_value);
			}

			if (dirty[0] & /*activeSkinTone*/ 32768 && div3_aria_activedescendant_value !== (div3_aria_activedescendant_value = "skintone-" + /*activeSkinTone*/ ctx[15])) {
				attr(div3, "aria-activedescendant", div3_aria_activedescendant_value);
			}

			if (dirty[0] & /*skinTonePickerExpanded*/ 2048 && div3_aria_hidden_value !== (div3_aria_hidden_value = !/*skinTonePickerExpanded*/ ctx[11])) {
				attr(div3, "aria-hidden", div3_aria_hidden_value);
			}

			if (dirty[0] & /*groups, i18n, searchMode, currentGroup, onNavClick*/ 1086324865) {
				const each_value_3 = /*groups*/ ctx[22];
				each_blocks_2 = update_keyed_each(each_blocks_2, dirty, get_key_1, 1, ctx, each_value_3, each1_lookup, div5, destroy_block, create_each_block_3, null, get_each_context_3);
			}

			if (dirty[0] & /*groups*/ 4194304) {
				set_style(div5, "grid-template-columns", "repeat(" + /*groups*/ ctx[22].length + ", 1fr)");
			}

			if (dirty[0] & /*i18n*/ 1 && div5_aria_label_value !== (div5_aria_label_value = /*i18n*/ ctx[0].categoriesLabel)) {
				attr(div5, "aria-label", div5_aria_label_value);
			}

			if (dirty[0] & /*indicatorStyle*/ 1024) {
				attr(div6, "style", /*indicatorStyle*/ ctx[10]);
			}

			if (dirty[0] & /*message*/ 512) set_data(t4, /*message*/ ctx[9]);

			if (dirty[0] & /*message*/ 512 && div8_class_value !== (div8_class_value = "message " + (/*message*/ ctx[9] ? "" : "gone"))) {
				attr(div8, "class", div8_class_value);
			}

			if (dirty[0] & /*searchMode, currentEmojisWithCategories, activeSearchItem, labelWithSkin, currentSkinTone, i18n, currentGroup*/ 75514245) {
				const each_value_1 = /*currentEmojisWithCategories*/ ctx[2];
				each_blocks_1 = update_keyed_each(each_blocks_1, dirty, get_key_2, 1, ctx, each_value_1, each2_lookup, div9, destroy_block, create_each_block_1, null, get_each_context_1);
			}

			if (dirty[0] & /*loaded, message*/ 16777728 && div9_class_value !== (div9_class_value = "tabpanel " + (!/*loaded*/ ctx[24] || /*message*/ ctx[9] ? "gone" : ""))) {
				attr(div9, "class", div9_class_value);
			}

			if (dirty[0] & /*searchMode*/ 128 && div9_role_value !== (div9_role_value = /*searchMode*/ ctx[7] ? "region" : "tabpanel")) {
				attr(div9, "role", div9_role_value);
			}

			if (dirty[0] & /*searchMode, i18n, currentGroup*/ 8388737 && div9_aria_label_value !== (div9_aria_label_value = /*searchMode*/ ctx[7]
			? /*i18n*/ ctx[0].searchResultsLabel
			: /*i18n*/ ctx[0].categories[/*currentGroup*/ ctx[23].name])) {
				attr(div9, "aria-label", div9_aria_label_value);
			}

			if (dirty[0] & /*searchMode, currentGroup*/ 8388736 && div9_id_value !== (div9_id_value = /*searchMode*/ ctx[7]
			? ""
			: `tab-${/*currentGroup*/ ctx[23].id}`)) {
				attr(div9, "id", div9_id_value);
			}

			if (dirty[0] & /*labelWithSkin, currentFavorites, currentSkinTone*/ 68173824) {
				const each_value = /*currentFavorites*/ ctx[20];
				each_blocks = update_keyed_each(each_blocks, dirty, get_key_3, 1, ctx, each_value, each3_lookup, div10, destroy_block, create_each_block, null, get_each_context);
			}

			if (dirty[0] & /*message*/ 512 && div10_class_value !== (div10_class_value = "favorites emoji-menu " + (/*message*/ ctx[9] ? "gone" : ""))) {
				attr(div10, "class", div10_class_value);
			}

			if (dirty[0] & /*i18n*/ 1 && div10_aria_label_value !== (div10_aria_label_value = /*i18n*/ ctx[0].favoritesLabel)) {
				attr(div10, "aria-label", div10_aria_label_value);
			}

			if (dirty[0] & /*scrollbarWidth*/ 2097152) {
				set_style(div10, "padding-right", /*scrollbarWidth*/ ctx[21] + "px");
			}

			if (dirty[0] & /*i18n*/ 1 && section_aria_label_value !== (section_aria_label_value = /*i18n*/ ctx[0].regionLabel)) {
				attr(section, "aria-label", section_aria_label_value);
			}

			if (dirty[0] & /*pickerStyle*/ 131072) {
				attr(section, "style", /*pickerStyle*/ ctx[17]);
			}
		},
		i: noop,
		o: noop,
		d(detaching) {
			if (detaching) detach(section);

			for (let i = 0; i < each_blocks_3.length; i += 1) {
				each_blocks_3[i].d();
			}

			/*div3_binding*/ ctx[45](null);

			for (let i = 0; i < each_blocks_2.length; i += 1) {
				each_blocks_2[i].d();
			}

			for (let i = 0; i < each_blocks_1.length; i += 1) {
				each_blocks_1[i].d();
			}

			/*div9_binding*/ ctx[47](null);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].d();
			}

			/*button1_binding*/ ctx[48](null);
			/*section_binding*/ ctx[49](null);
			mounted = false;
			run_all(dispose);
		}
	};
}

function unicodeWithSkin(emoji, currentSkinTone) {
	return currentSkinTone && emoji.skins && emoji.skins[currentSkinTone] || emoji.unicode;
}

function instance($$self, $$props, $$invalidate) {
	let { locale = null } = $$props;
	let { dataSource = null } = $$props;
	let { skinToneEmoji = DEFAULT_SKIN_TONE_EMOJI } = $$props;
	let { i18n = enI18n } = $$props;
	let { database = null } = $$props;
	let { customEmoji = null } = $$props;
	let { customCategorySorting = (a, b) => a < b ? -1 : a > b ? 1 : 0 } = $$props;

	// private
	let initialLoad = true;

	let currentEmojis = [];
	let currentEmojisWithCategories = []; // eslint-disable-line no-unused-vars
	let rawSearchText = "";
	let searchText = "";
	let rootElement;
	let baselineEmoji;
	let tabpanelElement;
	let searchMode = false; // eslint-disable-line no-unused-vars
	let activeSearchItem = -1;
	let message; // eslint-disable-line no-unused-vars
	let computedIndicatorWidth = 0;
	let indicatorStyle = ""; // eslint-disable-line no-unused-vars
	let skinTonePickerExpanded = false;
	let skinTonePickerExpandedAfterAnimation = false; // eslint-disable-line no-unused-vars
	let skinToneDropdown;
	let currentSkinTone = 0;
	let activeSkinTone = 0;
	let skinToneButtonText; // eslint-disable-line no-unused-vars
	let pickerStyle; // eslint-disable-line no-unused-vars
	let skinToneButtonLabel = ""; // eslint-disable-line no-unused-vars
	let skinTones = [];
	let currentFavorites = []; // eslint-disable-line no-unused-vars
	let defaultFavoriteEmojis;
	let numColumns = DEFAULT_NUM_COLUMNS;
	let scrollbarWidth = 0; // eslint-disable-line no-unused-vars
	let currentGroupIndex = 0;
	let groups$1 = groups;
	let currentGroup;
	let loaded = false; // eslint-disable-line no-unused-vars
	let activeSearchItemId; // eslint-disable-line no-unused-vars

	//
	// Utils/helpers
	//
	function focus(id) {
		rootElement.getRootNode().getElementById(id).focus();
	}

	// fire a custom event that crosses the shadow boundary
	function fireEvent(name, detail) {
		rootElement.dispatchEvent(new CustomEvent(name, { detail, bubbles: true, composed: true }));
	}

	// eslint-disable-next-line no-unused-vars
	function labelWithSkin(emoji, currentSkinTone) {
		return uniq([
			emoji.name || unicodeWithSkin(emoji, currentSkinTone),
			...emoji.shortcodes || []
		]).join(", ");
	}

	//
	// Determine the emoji support level (in requestIdleCallback)
	//
	emojiSupportLevelPromise.then(level => {
		if (!level) {
			$$invalidate(9, message = i18n.emojiUnsupportedMessage);
		}
	});

	// TODO: this is a bizarre way to set these default properties, but currently Svelte
	// renders custom elements in an odd way - props are not set when calling the constructor,
	// but are only set later. This would cause a double render or a double-fetch of
	// the dataSource, which is bad. Delaying with a microtask avoids this.
	// See https://github.com/sveltejs/svelte/pull/4527
	onMount(async () => {
		await tick();
		$$invalidate(38, locale = locale || DEFAULT_LOCALE);
		$$invalidate(39, dataSource = dataSource || DEFAULT_DATA_SOURCE);
	});

	onDestroy(async () => {
		if (database) {
			await database.close();
		}
	});

	//
	// Calculate the width of the emoji grid. This serves two purposes:
	// 1) Re-calculate the --num-columns var because it may have changed
	// 2) Re-calculate the scrollbar width because it may have changed
	//   (i.e. because the number of items changed)
	//
	// eslint-disable-next-line no-unused-vars
	function calculateEmojiGridWidth(node) {
		return calculateWidth(node, width => {
			const newNumColumns = "production" === "test"
			? DEFAULT_NUM_COLUMNS
			: parseInt(getComputedStyle(rootElement).getPropertyValue("--num-columns"), 10);

			const parentWidth = "production" === "test"
			? 1
			: node.parentElement.getBoundingClientRect().width; // jsdom throws an error here occasionally

			const newScrollbarWidth = parentWidth - width;
			$$invalidate(54, numColumns = newNumColumns);
			$$invalidate(21, scrollbarWidth = newScrollbarWidth);
		});
	}

	//
	// Animate the indicator
	//
	// eslint-disable-next-line no-unused-vars
	function calculateIndicatorWidth(node) {
		return calculateWidth(node, width => {
			$$invalidate(52, computedIndicatorWidth = width);
		});
	}

	function checkZwjSupportAndUpdate(zwjEmojisToCheck) {
		const rootNode = rootElement.getRootNode();

		// Jest doesn't seem to understand that shadowRoot.getElementById() is a thing
		const emojiToDomNode = "production" === "test"
		? emoji => rootNode.querySelector(`[id=${JSON.stringify("emo-" + emoji.id)}]`)
		: emoji => rootNode.getElementById(`emo-${emoji.id}`);

		checkZwjSupport(zwjEmojisToCheck, baselineEmoji, emojiToDomNode);

		// force update
		$$invalidate(1, currentEmojis = currentEmojis); // eslint-disable-line no-self-assign
	}

	function isZwjSupported(emoji) {
		return !emoji.unicode || !hasZwj(emoji) || supportedZwjEmojis.get(emoji.unicode);
	}

	async function filterEmojisByVersion(emojis) {
		const emojiSupportLevel = await emojiSupportLevelPromise;

		// !version corresponds to custom emoji
		return emojis.filter(({ version }) => !version || version <= emojiSupportLevel);
	}

	async function summarizeEmojis(emojis) {
		return summarizeEmojisForUI(emojis, await emojiSupportLevelPromise);
	}

	async function getEmojisByGroup(group) {

		if (typeof group === "undefined") {
			return [];
		}

		// -1 is custom emoji
		const emoji = group === -1
		? customEmoji
		: await database.getEmojiByGroup(group);

		return summarizeEmojis(await filterEmojisByVersion(emoji));
	}

	async function getEmojisBySearchQuery(query) {
		return summarizeEmojis(await filterEmojisByVersion(await database.getEmojiBySearchQuery(query)));
	}

	// eslint-disable-next-line no-unused-vars
	function onSearchKeydown(event) {
		if (!searchMode || !currentEmojis.length) {
			return;
		}

		const goToNextOrPrevious = previous => {
			halt(event);
			$$invalidate(8, activeSearchItem = incrementOrDecrement(previous, activeSearchItem, currentEmojis));
		};

		switch (event.key) {
			case "ArrowDown":
				return goToNextOrPrevious(false);
			case "ArrowUp":
				return goToNextOrPrevious(true);
			case "Enter":
				if (activeSearchItem !== -1) {
					halt(event);
					return clickEmoji(currentEmojis[activeSearchItem].id);
				} else if (currentEmojis.length) {
					$$invalidate(8, activeSearchItem = 0);
				}
		}
	}

	//
	// Handle user input on nav
	//
	// eslint-disable-next-line no-unused-vars
	function onNavClick(group) {
		$$invalidate(3, rawSearchText = "");
		$$invalidate(51, searchText = "");
		$$invalidate(8, activeSearchItem = -1);
		$$invalidate(55, currentGroupIndex = groups$1.findIndex(_ => _.id === group.id));
	}

	// eslint-disable-next-line no-unused-vars
	function onNavKeydown(event) {
		const { target, key } = event;

		const doFocus = el => {
			if (el) {
				halt(event);
				el.focus();
			}
		};

		switch (key) {
			case "ArrowLeft":
				return doFocus(target.previousSibling);
			case "ArrowRight":
				return doFocus(target.nextSibling);
			case "Home":
				return doFocus(target.parentElement.firstChild);
			case "End":
				return doFocus(target.parentElement.lastChild);
		}
	}

	//
	// Handle user input on an emoji
	//
	async function clickEmoji(unicodeOrName) {
		const emoji = await database.getEmojiByUnicodeOrName(unicodeOrName);
		const emojiSummary = [...currentEmojis, ...currentFavorites].find(_ => _.id === unicodeOrName);
		const skinTonedUnicode = emojiSummary.unicode && unicodeWithSkin(emojiSummary, currentSkinTone);
		await database.incrementFavoriteEmojiCount(unicodeOrName);

		// eslint-disable-next-line no-self-assign
		$$invalidate(53, defaultFavoriteEmojis = defaultFavoriteEmojis); // force favorites to re-render

		fireEvent("emoji-click", {
			emoji,
			skinTone: currentSkinTone,
			...skinTonedUnicode && { unicode: skinTonedUnicode },
			...emojiSummary.name && { name: emojiSummary.name }
		});
	}

	// eslint-disable-next-line no-unused-vars
	async function onEmojiClick(event) {
		const { target } = event;

		if (!target.classList.contains("emoji")) {
			return;
		}

		halt(event);
		const id = target.id.substring(4); // replace 'emo-' or 'fav-' prefix

		/* no await */
		clickEmoji(id);
	}

	//
	// Handle user input on the skintone picker
	//
	// eslint-disable-next-line no-unused-vars
	async function onSkinToneOptionsClick(event) {
		const { target } = event;

		if (!target.classList.contains("skintone-option")) {
			return;
		}

		halt(event);
		const skinTone = parseInt(target.id.slice(9), 10); // remove 'skintone-' prefix
		$$invalidate(14, currentSkinTone = skinTone);
		$$invalidate(11, skinTonePickerExpanded = false);
		focus("skintone-button");
		fireEvent("skin-tone-change", { skinTone });

		/* no await */
		database.setPreferredSkinTone(skinTone);
	}

	// eslint-disable-next-line no-unused-vars
	async function onClickSkinToneButton(event) {
		$$invalidate(11, skinTonePickerExpanded = !skinTonePickerExpanded);
		$$invalidate(15, activeSkinTone = currentSkinTone);

		if (skinTonePickerExpanded) {
			halt(event);
			rAF(() => focus(`skintone-${activeSkinTone}`));
		}
	}

	// eslint-disable-next-line no-unused-vars
	function onSkinToneOptionsKeydown(event) {
		if (!skinTonePickerExpanded) {
			return;
		}

		const changeActiveSkinTone = async nextSkinTone => {
			halt(event);
			$$invalidate(15, activeSkinTone = nextSkinTone);
			await tick();
			focus(`skintone-${activeSkinTone}`);
		};

		switch (event.key) {
			case "ArrowUp":
				return changeActiveSkinTone(incrementOrDecrement(true, activeSkinTone, skinTones));
			case "ArrowDown":
				return changeActiveSkinTone(incrementOrDecrement(false, activeSkinTone, skinTones));
			case "Home":
				return changeActiveSkinTone(0);
			case "End":
				return changeActiveSkinTone(skinTones.length - 1);
			case "Enter":
				// enter on keydown, space on keyup. this is just how browsers work for buttons
				// https://lists.w3.org/Archives/Public/w3c-wai-ig/2019JanMar/0086.html
				return onSkinToneOptionsClick(event);
			case "Escape":
				halt(event);
				return focus("skintone-button");
		}
	}

	// eslint-disable-next-line no-unused-vars
	function onSkinToneOptionsKeyup(event) {
		if (!skinTonePickerExpanded) {
			return;
		}

		switch (event.key) {
			case " ":
				// enter on keydown, space on keyup. this is just how browsers work for buttons
				// https://lists.w3.org/Archives/Public/w3c-wai-ig/2019JanMar/0086.html
				return onSkinToneOptionsClick(event);
		}
	}

	// eslint-disable-next-line no-unused-vars
	async function onSkinToneOptionsFocusOut(event) {
		// On blur outside of the skintone options, collapse the skintone picker.
		// Except if focus is just moving to another skintone option, e.g. pressing up/down to change focus
		const { relatedTarget } = event;

		if (!relatedTarget || !relatedTarget.classList.contains("skintone-option")) {
			$$invalidate(11, skinTonePickerExpanded = false);
		}
	}

	function input_input_handler() {
		rawSearchText = this.value;
		$$invalidate(3, rawSearchText);
	}

	function div3_binding($$value) {
		binding_callbacks[$$value ? "unshift" : "push"](() => {
			skinToneDropdown = $$value;
			$$invalidate(13, skinToneDropdown);
		});
	}

	const click_handler = group => onNavClick(group);

	function div9_binding($$value) {
		binding_callbacks[$$value ? "unshift" : "push"](() => {
			tabpanelElement = $$value;
			(((((((((($$invalidate(6, tabpanelElement), $$invalidate(1, currentEmojis)), $$invalidate(40, database)), $$invalidate(51, searchText)), $$invalidate(23, currentGroup)), $$invalidate(38, locale)), $$invalidate(39, dataSource)), $$invalidate(42, customEmoji)), $$invalidate(3, rawSearchText)), $$invalidate(22, groups$1)), $$invalidate(55, currentGroupIndex));
		});
	}

	function button1_binding($$value) {
		binding_callbacks[$$value ? "unshift" : "push"](() => {
			baselineEmoji = $$value;
			$$invalidate(5, baselineEmoji);
		});
	}

	function section_binding($$value) {
		binding_callbacks[$$value ? "unshift" : "push"](() => {
			rootElement = $$value;
			$$invalidate(4, rootElement);
		});
	}

	$$self.$$set = $$props => {
		if ("locale" in $$props) $$invalidate(38, locale = $$props.locale);
		if ("dataSource" in $$props) $$invalidate(39, dataSource = $$props.dataSource);
		if ("skinToneEmoji" in $$props) $$invalidate(41, skinToneEmoji = $$props.skinToneEmoji);
		if ("i18n" in $$props) $$invalidate(0, i18n = $$props.i18n);
		if ("database" in $$props) $$invalidate(40, database = $$props.database);
		if ("customEmoji" in $$props) $$invalidate(42, customEmoji = $$props.customEmoji);
		if ("customCategorySorting" in $$props) $$invalidate(43, customCategorySorting = $$props.customCategorySorting);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty[1] & /*locale, dataSource, database*/ 896) {
			 {
				if (locale && dataSource && (!database || database.locale !== locale && database.dataSource !== dataSource)) {
					$$invalidate(40, database = new Database({ dataSource, locale }));
				}
			}
		}

		if ($$self.$$.dirty[1] & /*customEmoji, database*/ 2560) {
			//
			// Set or update the customEmoji
			//
			 {
				if (customEmoji && database) {
					$$invalidate(40, database.customEmoji = customEmoji, database);
				}
			}
		}

		if ($$self.$$.dirty[0] & /*i18n, message*/ 513 | $$self.$$.dirty[1] & /*database*/ 512) {
			//
			// Set or update the database object
			//
			 {
				// show a Loading message if it takes a long time, or show an error if there's a network/IDB error
				async function handleDatabaseLoading() {
					const timeoutHandle = setTimeout(
						() => {
							$$invalidate(9, message = i18n.loadingMessage);
						},
						TIMEOUT_BEFORE_LOADING_MESSAGE
					);

					try {
						await database.ready();
						$$invalidate(24, loaded = true);
					} catch(err) {
						console.error(err);
						$$invalidate(9, message = i18n.networkErrorMessage);
					} finally {
						clearTimeout(timeoutHandle);

						if (message === i18n.loadingMessage) {
							$$invalidate(9, message = "");
						}
					}
				}

				if (database) {
					/* no await */
					handleDatabaseLoading();
				}
			}
		}

		if ($$self.$$.dirty[0] & /*groups*/ 4194304 | $$self.$$.dirty[1] & /*customEmoji*/ 2048) {
			 {
				if (customEmoji && customEmoji.length) {
					$$invalidate(22, groups$1 = [customGroup, ...groups]);
				} else if (groups$1 !== groups) {
					$$invalidate(22, groups$1 = groups);
				}
			}
		}

		if ($$self.$$.dirty[0] & /*rawSearchText*/ 8) {
			//
			// Handle user input on the search input
			//
			 {
				rIC(() => {
					$$invalidate(51, searchText = (rawSearchText || "").trim()); // defer to avoid input delays, plus we can trim here
					$$invalidate(8, activeSearchItem = -1);
				});
			}
		}

		if ($$self.$$.dirty[0] & /*groups*/ 4194304 | $$self.$$.dirty[1] & /*currentGroupIndex*/ 16777216) {
			//
			// Update the current group based on the currentGroupIndex
			//
			 $$invalidate(23, currentGroup = groups$1[currentGroupIndex]);
		}

		if ($$self.$$.dirty[0] & /*currentGroup*/ 8388608 | $$self.$$.dirty[1] & /*database, searchText*/ 1049088) {
			//
			// Set or update the currentEmojis. Check for invalid ZWJ renderings
			// (i.e. double emoji).
			//
			 {
				async function updateEmojis() {

					if (!database) {
						$$invalidate(1, currentEmojis = []);
						$$invalidate(7, searchMode = false);
					} else if (searchText.length >= MIN_SEARCH_TEXT_LENGTH) {
						$$invalidate(1, currentEmojis = await getEmojisBySearchQuery(searchText));
						$$invalidate(7, searchMode = true);
					} else if (currentGroup) {
						$$invalidate(1, currentEmojis = await getEmojisByGroup(currentGroup.id));
						$$invalidate(7, searchMode = false);
					}
				}

				/* no await */
				updateEmojis();
			}
		}

		if ($$self.$$.dirty[0] & /*groups, searchMode*/ 4194432) {
			//
			// Global styles for the entire picker
			//
			 $$invalidate(17, pickerStyle = `
  --font-family: ${FONT_FAMILY};
  --num-groups: ${groups$1.length}; 
  --indicator-opacity: ${searchMode ? 0 : 1}; 
  --num-skintones: ${NUM_SKIN_TONES};`);
		}

		if ($$self.$$.dirty[1] & /*database*/ 512) {
			//
			// Set or update the preferred skin tone
			//
			 {
				async function updatePreferredSkinTone() {
					if (database) {
						$$invalidate(14, currentSkinTone = await database.getPreferredSkinTone());
					}
				}

				/* no await */
				updatePreferredSkinTone();
			}
		}

		if ($$self.$$.dirty[1] & /*skinToneEmoji*/ 1024) {
			 $$invalidate(19, skinTones = Array(NUM_SKIN_TONES).fill().map((_, i) => applySkinTone(skinToneEmoji, i)));
		}

		if ($$self.$$.dirty[0] & /*skinTones, currentSkinTone*/ 540672) {
			 $$invalidate(16, skinToneButtonText = skinTones[currentSkinTone]);
		}

		if ($$self.$$.dirty[0] & /*i18n, currentSkinTone*/ 16385) {
			 $$invalidate(18, skinToneButtonLabel = i18n.skinToneLabel.replace("{skinTone}", i18n.skinTones[currentSkinTone]));
		}

		if ($$self.$$.dirty[1] & /*database*/ 512) {
			//
			// Set or update the favorites emojis
			//
			 {
				async function updateDefaultFavoriteEmojis() {
					$$invalidate(53, defaultFavoriteEmojis = (await Promise.all(MOST_COMMONLY_USED_EMOJI.map(unicode => database.getEmojiByUnicodeOrName(unicode)))).filter(Boolean)); // filter because in Jest tests we don't have all the emoji in the DB
				}

				if (database) {
					/* no await */
					updateDefaultFavoriteEmojis();
				}
			}
		}

		if ($$self.$$.dirty[1] & /*database, numColumns, defaultFavoriteEmojis*/ 12583424) {
			 {
				async function updateFavorites() {
					const dbFavorites = await database.getTopFavoriteEmoji(numColumns);
					const favorites = await summarizeEmojis(uniqBy([...dbFavorites, ...defaultFavoriteEmojis], _ => _.unicode || _.name).slice(0, numColumns));
					$$invalidate(20, currentFavorites = favorites);
				}

				if (database && defaultFavoriteEmojis) {
					/* no await */
					updateFavorites();
				}
			}
		}

		if ($$self.$$.dirty[1] & /*currentGroupIndex, computedIndicatorWidth*/ 18874368) {
			// TODO: Chrome has an unfortunate bug where we can't use a simple percent-based transform
			// here, becuause it's janky. You can especially see this on a Nexus 5.
			// So we calculate of the indicator and use exact pixel values in the animation instead
			// (where ResizeObserver is supported).
			 {
				/* istanbul ignore if */
				if (resizeObserverSupported) {
					$$invalidate(10, indicatorStyle = `transform: translateX(${currentGroupIndex * computedIndicatorWidth}px);`); // exact pixels
				} else {
					$$invalidate(10, indicatorStyle = `transform: translateX(${currentGroupIndex * 100}%);`); // fallback to percent-based
				}
			}
		}

		if ($$self.$$.dirty[0] & /*currentEmojis*/ 2) {
			// Some emojis have their ligatures rendered as two or more consecutive emojis
			// We want to treat these the same as unsupported emojis, so we compare their
			// widths against the baseline widths and remove them as necessary
			 {
				const zwjEmojisToCheck = currentEmojis.filter(emoji => emoji.unicode).filter(emoji => hasZwj(emoji) && !supportedZwjEmojis.has(emoji.unicode)); // filter custom emoji

				if (zwjEmojisToCheck.length) {
					// render now, check their length later
					rAF(() => checkZwjSupportAndUpdate(zwjEmojisToCheck));
				} else {
					$$invalidate(1, currentEmojis = currentEmojis.filter(isZwjSupported));

					rAF(() => {
						// reset scroll top to 0 when emojis change
						if ("production" !== "test") {
							$$invalidate(6, tabpanelElement.scrollTop = 0, tabpanelElement);
						}
					});
				}
			}
		}

		if ($$self.$$.dirty[0] & /*currentEmojis, currentFavorites*/ 1048578 | $$self.$$.dirty[1] & /*initialLoad*/ 524288) {
			 {
				// consider initialLoad to be complete when the first tabpanel and favorites are rendered
				if ("production" !== "production" || false) {
					if (currentEmojis.length && currentFavorites.length && initialLoad) {
						$$invalidate(50, initialLoad = false);
						requestPostAnimationFrame(() => stop());
					}
				}
			}
		}

		if ($$self.$$.dirty[0] & /*searchMode, currentEmojis*/ 130 | $$self.$$.dirty[1] & /*customCategorySorting*/ 4096) {
			//
			// Derive currentEmojisWithCategories from currentEmojis. This is always done even if there
			// are no categories, because it's just easier to code the HTML this way.
			//
			 {
				function calculateCurrentEmojisWithCategories() {
					if (searchMode) {
						return [{ category: "", emojis: currentEmojis }];
					}

					const categoriesToEmoji = new Map();

					for (const emoji of currentEmojis) {
						const category = emoji.category || "";
						let emojis = categoriesToEmoji.get(category);

						if (!emojis) {
							emojis = [];
							categoriesToEmoji.set(category, emojis);
						}

						emojis.push(emoji);
					}

					return [...categoriesToEmoji.entries()].map(([category, emojis]) => ({ category, emojis })).sort((a, b) => customCategorySorting(a.category, b.category));
				}

				$$invalidate(2, currentEmojisWithCategories = calculateCurrentEmojisWithCategories());
			}
		}

		if ($$self.$$.dirty[0] & /*activeSearchItem, currentEmojis*/ 258) {
			//
			// Handle active search item (i.e. pressing up or down while searching)
			//
			 $$invalidate(25, activeSearchItemId = activeSearchItem !== -1 && currentEmojis[activeSearchItem].id);
		}

		if ($$self.$$.dirty[0] & /*skinTonePickerExpanded, skinToneDropdown*/ 10240) {
			// To make the animation nicer, change the z-index of the skintone picker button
			// *after* the animation has played. This makes it appear that the picker box
			// is expanding "below" the button
			 {
				if (skinTonePickerExpanded) {
					skinToneDropdown.addEventListener(
						"transitionend",
						() => {
							$$invalidate(12, skinTonePickerExpandedAfterAnimation = true);
						},
						{ once: true }
					);
				} else {
					$$invalidate(12, skinTonePickerExpandedAfterAnimation = false);
				}
			}
		}
	};

	return [
		i18n,
		currentEmojis,
		currentEmojisWithCategories,
		rawSearchText,
		rootElement,
		baselineEmoji,
		tabpanelElement,
		searchMode,
		activeSearchItem,
		message,
		indicatorStyle,
		skinTonePickerExpanded,
		skinTonePickerExpandedAfterAnimation,
		skinToneDropdown,
		currentSkinTone,
		activeSkinTone,
		skinToneButtonText,
		pickerStyle,
		skinToneButtonLabel,
		skinTones,
		currentFavorites,
		scrollbarWidth,
		groups$1,
		currentGroup,
		loaded,
		activeSearchItemId,
		labelWithSkin,
		calculateEmojiGridWidth,
		calculateIndicatorWidth,
		onSearchKeydown,
		onNavClick,
		onNavKeydown,
		onEmojiClick,
		onSkinToneOptionsClick,
		onClickSkinToneButton,
		onSkinToneOptionsKeydown,
		onSkinToneOptionsKeyup,
		onSkinToneOptionsFocusOut,
		locale,
		dataSource,
		database,
		skinToneEmoji,
		customEmoji,
		customCategorySorting,
		input_input_handler,
		div3_binding,
		click_handler,
		div9_binding,
		button1_binding,
		section_binding
	];
}

class Picker extends SvelteElement {
	constructor(options) {
		super();
		this.shadowRoot.innerHTML = `<style>:host{--emoji-padding:0.5rem;--emoji-size:1.375rem;--indicator-height:3px;--input-border-radius:0.5rem;--input-border-size:1px;--input-font-size:1rem;--input-line-height:1.5;--input-padding:0.25rem;--num-columns:8;--outline-size:2px;--border-size:1px;--skintone-border-radius:1rem;--category-font-size:1rem;display:flex;width:min-content;height:400px}:host,:host(.light){--background:#fff;--border-color:#e0e0e0;--indicator-color:#385ac1;--input-border-color:#999;--input-font-color:#111;--input-placeholder-color:#999;--outline-color:#999;--category-font-color:#111;--button-active-background:#e6e6e6;--button-hover-background:#d9d9d9}:host(.dark){--background:#222;--border-color:#444;--indicator-color:#5373ec;--input-border-color:#ccc;--input-font-color:#efefef;--input-placeholder-color:#ccc;--outline-color:#fff;--category-font-color:#efefef;--button-active-background:#555;--button-hover-background:#484848}@media(prefers-color-scheme:dark){:host{--background:#222;--border-color:#444;--indicator-color:#5373ec;--input-border-color:#ccc;--input-font-color:#efefef;--input-placeholder-color:#ccc;--outline-color:#fff;--category-font-color:#efefef;--button-active-background:#555;--button-hover-background:#484848}}button{margin:0;padding:0;border:none;background:none;box-shadow:none;cursor:pointer;-webkit-tap-highlight-color:transparent}button::-moz-focus-inner{border:0}input{padding:0;margin:0;line-height:1.15;font-family:inherit}input[type=search]{-webkit-appearance:none}:focus{outline:var(--outline-color) solid var(--outline-size);outline-offset:calc(-1*var(--outline-size))}:host([data-js-focus-visible]) :focus:not([data-focus-visible-added]){outline:none}:focus:not(:focus-visible){outline:none}.hide-focus{outline:none}*{box-sizing:border-box}.picker{contain:content;display:flex;flex-direction:column;background:var(--background);border:var(--border-size) solid var(--border-color);width:100%;height:100%;overflow:hidden;--total-emoji-size:calc(var(--emoji-size) + 2*var(--emoji-padding))}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}.hidden{opacity:0;pointer-events:none}.abs-pos{position:absolute;left:0;top:0}.gone{display:none!important}.cursor-pointer{cursor:pointer}.skintone-button-wrapper{background:var(--background);z-index:3}.skintone-button-wrapper.expanded{z-index:1}.skintone-list{position:absolute;right:0;top:0;z-index:2;overflow:visible;background:var(--background);border-bottom:var(--border-size) solid var(--border-color);border-radius:0 0 var(--skintone-border-radius) var(--skintone-border-radius);will-change:transform;transition:transform .2s ease-in-out;transform-origin:center 0}@media(prefers-reduced-motion:reduce){.skintone-list{transition-duration:1ms}}.skintone-list.no-animate{transition:none}.tabpanel{overflow-y:auto;-webkit-overflow-scrolling:touch;will-change:transform;min-height:0;flex:1;contain:content}.emoji-menu{display:grid;grid-template-columns:repeat(var(--num-columns),var(--total-emoji-size));justify-content:space-around;align-items:flex-start;width:100%}.category{padding:var(--emoji-padding);font-size:var(--category-font-size);color:var(--category-font-color)}.emoji,button.emoji{font-size:var(--emoji-size);display:flex;align-items:center;justify-content:center;border-radius:100%;height:var(--total-emoji-size);width:var(--total-emoji-size);line-height:1;overflow:hidden;font-family:var(--font-family)}@media(hover:hover) and (pointer:fine){.emoji:hover,button.emoji:hover{background:var(--button-hover-background)}}.emoji.active,.emoji:active,button.emoji.active,button.emoji:active{background:var(--button-active-background)}.custom-emoji{height:var(--total-emoji-size);width:var(--total-emoji-size);padding:var(--emoji-padding);object-fit:contain;pointer-events:none;background-repeat:no-repeat;background-position:50%;background-size:var(--emoji-size) var(--emoji-size)}.nav{display:grid;justify-content:space-between;contain:content}.nav,.nav-button{align-items:center}.nav-button{display:flex;justify-content:center}.indicator-wrapper{display:flex;border-bottom:1px solid var(--border-color)}.indicator{width:calc(100%/var(--num-groups));height:var(--indicator-height);opacity:var(--indicator-opacity);background-color:var(--indicator-color);will-change:transform,opacity;transition:opacity .1s linear,transform .25s ease-in-out}@media(prefers-reduced-motion:reduce){.indicator{will-change:opacity;transition:opacity .1s linear}}.pad-top{width:100%;height:var(--emoji-padding);z-index:3;background:var(--background)}.search-row{display:flex;align-items:center;position:relative;padding-left:var(--emoji-padding);padding-bottom:var(--emoji-padding)}.search-wrapper{flex:1;min-width:0}input.search{padding:var(--input-padding);border-radius:var(--input-border-radius);border:var(--input-border-size) solid var(--input-border-color);background:var(--background);color:var(--input-font-color);width:100%;font-size:var(--input-font-size);line-height:var(--input-line-height)}input.search::placeholder{color:var(--input-placeholder-color)}.favorites{display:flex;flex-direction:row;border-top:var(--border-size) solid var(--border-color);contain:content}.message{padding:var(--emoji-padding)}</style>`;

		init(
			this,
			{ target: this.shadowRoot },
			instance,
			create_fragment,
			safe_not_equal,
			{
				locale: 38,
				dataSource: 39,
				skinToneEmoji: 41,
				i18n: 0,
				database: 40,
				customEmoji: 42,
				customCategorySorting: 43
			},
			[-1, -1, -1]
		);

		if (options) {
			if (options.target) {
				insert(options.target, this, options.anchor);
			}

			if (options.props) {
				this.$set(options.props);
				flush();
			}
		}
	}

	static get observedAttributes() {
		return [
			"locale",
			"dataSource",
			"skinToneEmoji",
			"i18n",
			"database",
			"customEmoji",
			"customCategorySorting"
		];
	}

	get locale() {
		return this.$$.ctx[38];
	}

	set locale(locale) {
		this.$set({ locale });
		flush();
	}

	get dataSource() {
		return this.$$.ctx[39];
	}

	set dataSource(dataSource) {
		this.$set({ dataSource });
		flush();
	}

	get skinToneEmoji() {
		return this.$$.ctx[41];
	}

	set skinToneEmoji(skinToneEmoji) {
		this.$set({ skinToneEmoji });
		flush();
	}

	get i18n() {
		return this.$$.ctx[0];
	}

	set i18n(i18n) {
		this.$set({ i18n });
		flush();
	}

	get database() {
		return this.$$.ctx[40];
	}

	set database(database) {
		this.$set({ database });
		flush();
	}

	get customEmoji() {
		return this.$$.ctx[42];
	}

	set customEmoji(customEmoji) {
		this.$set({ customEmoji });
		flush();
	}

	get customCategorySorting() {
		return this.$$.ctx[43];
	}

	set customCategorySorting(customCategorySorting) {
		this.$set({ customCategorySorting });
		flush();
	}
}

class Picker$1 extends Picker {
  constructor (props) {
    // Make the API simpler, directly pass in the props
    super({ props });
  }

  disconnectedCallback () {
    this.$destroy();
  }

  static get observedAttributes () {
    return ['locale', 'data-source', 'skin-tone-emoji'] // complex objects aren't supported, also use kebab-case
  }

  // via https://github.com/sveltejs/svelte/issues/3852#issuecomment-665037015
  attributeChangedCallback (attrName, oldValue, newValue) {
    super.attributeChangedCallback(
      attrName.replace(/-([a-z])/g, (_, up) => up.toUpperCase()),
      oldValue,
      newValue
    );
  }
}

customElements.define('emoji-picker', Picker$1);

export default Picker$1;

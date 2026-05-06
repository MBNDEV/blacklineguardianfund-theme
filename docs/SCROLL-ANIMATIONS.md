# Scroll Animations — Implementation Guide

The theme ships an Elementor-style entrance-animation system for blocks. The animation set, duration tiers, and delay control mirror what Elementor exposes under **Advanced → Motion Effects**, with keyframes derived from [Animate.css](https://animate.style/) (MIT, Daniel Eden) — the same library Elementor builds on.

Animations fire **once** when the block scrolls into view. Above-the-fold blocks are revealed instantly on first paint (no animation on refresh). Users who enable *Reduce Motion* in their OS see everything immediately, no transition.

---

## How It Works

| File | Role |
|---|---|
| [`assets/css/scroll-animations.css`](../assets/css/scroll-animations.css) | Hidden state, visible state, all keyframes, duration variants |
| [`assets/js/scroll-animations.js`](../assets/js/scroll-animations.js) | jQuery + IntersectionObserver — adds `.is-visible` to elements as they enter the viewport |
| [`functions.php`](../functions.php) | Enqueues both assets on `wp_enqueue_scripts` (frontend only) |

The render layer puts three optional data attributes on the block wrapper:

| Attribute | Values | Effect |
|---|---|---|
| `data-animate` | `fadeInUp`, `zoomIn`, `bounceInLeft`, `rotateIn`, `lightSpeedIn`, `rollIn`, … (full list below) | Selects the keyframe |
| `data-animate-duration` | `slow` (2s), `fast` (0.75s), or omitted (1.25s normal) | Duration tier |
| `data-animate-delay` | Integer milliseconds, e.g. `200` | Wait before playing — useful for staggering siblings |

---

## Available Animations

| Group | Variants |
|---|---|
| **Fading** | `fadeIn`, `fadeInDown`, `fadeInLeft`, `fadeInRight`, `fadeInUp` |
| **Zooming** | `zoomIn`, `zoomInDown`, `zoomInLeft`, `zoomInRight`, `zoomInUp` |
| **Bouncing** | `bounceIn`, `bounceInDown`, `bounceInLeft`, `bounceInRight`, `bounceInUp` |
| **Sliding** | `slideInDown`, `slideInLeft`, `slideInRight`, `slideInUp` |
| **Rotating** | `rotateIn`, `rotateInDownLeft`, `rotateInDownRight`, `rotateInUpLeft`, `rotateInUpRight` |
| **Light Speed** | `lightSpeedIn` |
| **Specials** | `rollIn` |

### Backward-compatible legacy values

These older theme values still work — they're aliased internally to the Elementor names:

| Old | New |
|---|---|
| `fade-up` | `fadeInUp` |
| `fade-down` | `fadeInDown` |
| `fade-left` | `fadeInLeft` |
| `fade-right` | `fadeInRight` |
| `fade-in` | `fadeIn` |

Existing pages with the old values keep working without re-saving.

---

## Adding Animation Controls to a Block

The pattern is the same for every block. The reference implementation lives in [`blocks/intro-section`](../blocks/intro-section).

### 1. `block.json` — register three attributes

```json
"animationType": {
  "type": "string",
  "default": "fadeInUp"
},
"animationDuration": {
  "type": "string",
  "default": ""
},
"animationDelay": {
  "type": "number",
  "default": 0
}
```

### 2. `edit.js` — add the Motion Effects panel

```js
import { PanelBody, SelectControl, __experimentalNumberControl as NumberControl } from '@wordpress/components';

const { animationType, animationDuration, animationDelay } = attributes;
```

Inside `<InspectorControls>`:

```jsx
<PanelBody title={ __( 'Motion Effects', 'mbn-theme' ) } initialOpen={ false }>
  <SelectControl
    label={ __( 'Entrance Animation', 'mbn-theme' ) }
    value={ animationType }
    options={ [
      { label: 'None', value: '' },

      { label: 'Fading: Fade In',       value: 'fadeIn' },
      { label: 'Fading: Fade In Down',  value: 'fadeInDown' },
      { label: 'Fading: Fade In Left',  value: 'fadeInLeft' },
      { label: 'Fading: Fade In Right', value: 'fadeInRight' },
      { label: 'Fading: Fade In Up',    value: 'fadeInUp' },

      { label: 'Zooming: Zoom In',       value: 'zoomIn' },
      { label: 'Zooming: Zoom In Down',  value: 'zoomInDown' },
      { label: 'Zooming: Zoom In Left',  value: 'zoomInLeft' },
      { label: 'Zooming: Zoom In Right', value: 'zoomInRight' },
      { label: 'Zooming: Zoom In Up',    value: 'zoomInUp' },

      { label: 'Bouncing: Bounce In',       value: 'bounceIn' },
      { label: 'Bouncing: Bounce In Down',  value: 'bounceInDown' },
      { label: 'Bouncing: Bounce In Left',  value: 'bounceInLeft' },
      { label: 'Bouncing: Bounce In Right', value: 'bounceInRight' },
      { label: 'Bouncing: Bounce In Up',    value: 'bounceInUp' },

      { label: 'Sliding: Slide In Down',  value: 'slideInDown' },
      { label: 'Sliding: Slide In Left',  value: 'slideInLeft' },
      { label: 'Sliding: Slide In Right', value: 'slideInRight' },
      { label: 'Sliding: Slide In Up',    value: 'slideInUp' },

      { label: 'Rotating: Rotate In',            value: 'rotateIn' },
      { label: 'Rotating: Rotate In Down Left',  value: 'rotateInDownLeft' },
      { label: 'Rotating: Rotate In Down Right', value: 'rotateInDownRight' },
      { label: 'Rotating: Rotate In Up Left',    value: 'rotateInUpLeft' },
      { label: 'Rotating: Rotate In Up Right',   value: 'rotateInUpRight' },

      { label: 'Light Speed: Light Speed In', value: 'lightSpeedIn' },
      { label: 'Specials: Roll In',           value: 'rollIn' },
    ] }
    onChange={ ( value ) => setAttributes( { animationType: value } ) }
  />

  { animationType && (
    <>
      <SelectControl
        label={ __( 'Animation Duration', 'mbn-theme' ) }
        value={ animationDuration }
        options={ [
          { label: 'Normal (1.25s)', value: '' },
          { label: 'Slow (2s)',      value: 'slow' },
          { label: 'Fast (0.75s)',   value: 'fast' },
        ] }
        onChange={ ( value ) => setAttributes( { animationDuration: value } ) }
      />
      <NumberControl
        label={ __( 'Animation Delay (ms)', 'mbn-theme' ) }
        value={ animationDelay }
        onChange={ ( value ) => setAttributes( { animationDelay: parseInt( value, 10 ) || 0 } ) }
        min={ 0 }
        step={ 50 }
      />
    </>
  ) }
</PanelBody>
```

### 3. `render.php` — emit the data attributes

```php
$animation_type     = $attributes['animationType'] ?? '';
$animation_duration = $attributes['animationDuration'] ?? '';
$animation_delay    = (int) ( $attributes['animationDelay'] ?? 0 );

$anim_attr = array();
if ( $animation_type ) {
  $anim_attr['data-animate'] = $animation_type;
  if ( $animation_duration ) {
    $anim_attr['data-animate-duration'] = $animation_duration;
  }
  if ( $animation_delay > 0 ) {
    $anim_attr['data-animate-delay'] = (string) $animation_delay;
  }
}

$wrapper_attributes = get_block_wrapper_attributes(
  array_merge(
    array( 'class' => 'your-block-classes' ),
    $anim_attr
  )
);
```

Output:

```php
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
```

### 4. Build

```bash
npm run build:blocks
# or for a single block:
BLOCK=intro-section npm run build:blocks
```

No CSS rebuild needed — `assets/css/scroll-animations.css` is enqueued directly, not run through Tailwind.

---

## Stagger Children

Add `data-animate-stagger` to the parent and `data-animate` (with any variant) to each child. Each child gets an additional `100ms × index` delay automatically. Children's own `data-animate-delay` value is added to the stagger delay, not replaced.

```php
<div class="grid grid-cols-3" data-animate-stagger>
  <?php foreach ( $cards as $card ) : ?>
    <div data-animate="fadeInUp">
      <?php echo esc_html( $card['title'] ); ?>
    </div>
  <?php endforeach; ?>
</div>
```

The stagger parent itself should not have `data-animate` — only the children.

---

## Behavior Summary

| Scenario | Result |
|---|---|
| Refresh — block already on screen | Shown instantly, no animation |
| Scroll down — block enters viewport | Animation plays once |
| User has *Reduce Motion* enabled | Everything visible immediately, no transition |
| Browser without IntersectionObserver | Falls back to instant reveal of everything |

---

## Customising the CSS

`assets/css/scroll-animations.css` is the single source for all motion. Edit it directly to:

- Change the default duration: edit `--animation-duration` in `[data-animate].is-visible`
- Add a new keyframe: define a new `@keyframes` block + matching `[data-animate="<name>"].is-visible { animation-name: <name>; }` selector
- Soften the slide distance: replace `translate3d(0, 100%, 0)` with `translate3d(0, 30px, 0)` in the `fadeInUp` keyframe (see [Element.how guide](https://element.how/elementor-improve-entrance-animations/) — same trick, applies the moment you want subtler motion)

---

Sources:
- [Elementor — Add entrance animations](https://elementor.com/help/entrance-animations/)
- [Elementor — `animation.php` source (full variant list)](https://github.com/elementor/elementor/blob/master/includes/controls/animation.php)
- [Animate.css](https://animate.style/) — keyframe library

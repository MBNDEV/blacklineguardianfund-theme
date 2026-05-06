/**
 * Shared Animation Controls Component
 * 
 * Reusable Motion Effects panel for Gutenberg blocks.
 * Provides Elementor-style entrance animations with duration and delay controls.
 * 
 * Usage in edit.js:
 * 
 *   import AnimationControls from '../shared/AnimationControls';
 * 
 *   export default function Edit({ attributes, setAttributes }) {
 *     const { animationType, animationDuration, animationDelay } = attributes;
 *     // ...
 *     return (
 *       <Fragment>
 *         <InspectorControls>
 *           <AnimationControls
 *             animationType={animationType}
 *             animationDuration={animationDuration}
 *             animationDelay={animationDelay}
 *             setAttributes={setAttributes}
 *           />
 *         </InspectorControls>
 *       </Fragment>
 *     );
 *   }
 * 
 * Required attributes in block.json:
 *   "animationType": { "type": "string", "default": "fadeInUp" },
 *   "animationDuration": { "type": "string", "default": "" },
 *   "animationDelay": { "type": "number", "default": 0 }
 * 
 * @package CustomTheme
 */

import { PanelBody, SelectControl, __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

export default function AnimationControls({ animationType, animationDuration, animationDelay, setAttributes }) {
  return (
    <PanelBody title={__('Motion Effects', 'mbn-theme')} initialOpen={false}>
      <SelectControl
        label={__('Entrance Animation', 'mbn-theme')}
        value={animationType}
        options={[
          { label: __('None', 'mbn-theme'), value: '' },

          { label: __('Fading: Fade In', 'mbn-theme'),       value: 'fadeIn' },
          { label: __('Fading: Fade In Down', 'mbn-theme'),  value: 'fadeInDown' },
          { label: __('Fading: Fade In Left', 'mbn-theme'),  value: 'fadeInLeft' },
          { label: __('Fading: Fade In Right', 'mbn-theme'), value: 'fadeInRight' },
          { label: __('Fading: Fade In Up', 'mbn-theme'),    value: 'fadeInUp' },

          { label: __('Zooming: Zoom In', 'mbn-theme'),       value: 'zoomIn' },
          { label: __('Zooming: Zoom In Down', 'mbn-theme'),  value: 'zoomInDown' },
          { label: __('Zooming: Zoom In Left', 'mbn-theme'),  value: 'zoomInLeft' },
          { label: __('Zooming: Zoom In Right', 'mbn-theme'), value: 'zoomInRight' },
          { label: __('Zooming: Zoom In Up', 'mbn-theme'),    value: 'zoomInUp' },

          { label: __('Bouncing: Bounce In', 'mbn-theme'),       value: 'bounceIn' },
          { label: __('Bouncing: Bounce In Down', 'mbn-theme'),  value: 'bounceInDown' },
          { label: __('Bouncing: Bounce In Left', 'mbn-theme'),  value: 'bounceInLeft' },
          { label: __('Bouncing: Bounce In Right', 'mbn-theme'), value: 'bounceInRight' },
          { label: __('Bouncing: Bounce In Up', 'mbn-theme'),    value: 'bounceInUp' },

          { label: __('Sliding: Slide In Down', 'mbn-theme'),  value: 'slideInDown' },
          { label: __('Sliding: Slide In Left', 'mbn-theme'),  value: 'slideInLeft' },
          { label: __('Sliding: Slide In Right', 'mbn-theme'), value: 'slideInRight' },
          { label: __('Sliding: Slide In Up', 'mbn-theme'),    value: 'slideInUp' },

          { label: __('Rotating: Rotate In', 'mbn-theme'),             value: 'rotateIn' },
          { label: __('Rotating: Rotate In Down Left', 'mbn-theme'),   value: 'rotateInDownLeft' },
          { label: __('Rotating: Rotate In Down Right', 'mbn-theme'),  value: 'rotateInDownRight' },
          { label: __('Rotating: Rotate In Up Left', 'mbn-theme'),     value: 'rotateInUpLeft' },
          { label: __('Rotating: Rotate In Up Right', 'mbn-theme'),    value: 'rotateInUpRight' },

          { label: __('Light Speed: Light Speed In', 'mbn-theme'), value: 'lightSpeedIn' },
          { label: __('Specials: Roll In', 'mbn-theme'),           value: 'rollIn' },
        ]}
        onChange={(value) => setAttributes({ animationType: value })}
        help={__('Plays once when the section scrolls into view.', 'mbn-theme')}
      />

      {animationType && (
        <Fragment>
          <SelectControl
            label={__('Animation Duration', 'mbn-theme')}
            value={animationDuration}
            options={[
              { label: __('Normal (1.25s)', 'mbn-theme'), value: '' },
              { label: __('Slow (2s)', 'mbn-theme'),      value: 'slow' },
              { label: __('Fast (0.75s)', 'mbn-theme'),   value: 'fast' },
            ]}
            onChange={(value) => setAttributes({ animationDuration: value })}
          />

          <NumberControl
            label={__('Animation Delay (ms)', 'mbn-theme')}
            value={animationDelay}
            onChange={(value) => setAttributes({ animationDelay: parseInt(value, 10) || 0 })}
            min={0}
            step={50}
            help={__('Wait this many milliseconds after the section enters view before animating.', 'mbn-theme')}
          />
        </Fragment>
      )}
    </PanelBody>
  );
}

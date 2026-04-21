import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, RangeControl, TextControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundImageUrl, 
    backgroundImageId, 
    overlayOpacity,
    overlayBreakpoint,
    subheading,
    heading,
    primaryButtonText,
    primaryButtonUrl,
    secondaryButtonText,
    secondaryButtonUrl
  } = attributes;

  const blockProps = useBlockProps({
    className: 'hero-banner-editor relative w-full flex items-end',
    style: {
      backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      minHeight: '600px',
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Background Image', 'mbn-theme')} initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                backgroundImageUrl: media.url, 
                backgroundImageId: media.id 
              })}
              allowedTypes={['image']}
              value={backgroundImageId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {backgroundImageUrl 
                      ? __('Replace Background Image', 'mbn-theme') 
                      : __('Select Background Image', 'mbn-theme')
                    }
                  </Button>
                  {backgroundImageUrl && (
                    <div className="mt-4">
                      <img 
                        src={backgroundImageUrl} 
                        alt="" 
                        className="w-full h-32 object-cover rounded"
                      />
                    </div>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>
        
        <PanelBody title={__('Overlay Settings', 'mbn-theme')} initialOpen={false}>
          <RangeControl
            label={__('Overlay Opacity (%)', 'mbn-theme')}
            value={overlayOpacity}
            onChange={(value) => setAttributes({ overlayOpacity: value })}
            min={0}
            max={100}
            step={5}
          />
          <SelectControl
            label={__('Hide Overlay Above', 'mbn-theme')}
            value={overlayBreakpoint}
            options={[
              { label: __('Never (Always Show)', 'mbn-theme'), value: 'always' },
              { label: __('640px (sm)', 'mbn-theme'), value: 'sm' },
              { label: __('768px (md)', 'mbn-theme'), value: 'md' },
              { label: __('1024px (lg)', 'mbn-theme'), value: 'lg' },
              { label: __('1280px (xl)', 'mbn-theme'), value: 'xl' },
              { label: __('1536px (2xl)', 'mbn-theme'), value: '2xl' },
            ]}
            onChange={(value) => setAttributes({ overlayBreakpoint: value })}
            help={__('Overlay is visible below the selected breakpoint and hidden above it.', 'mbn-theme')}
          />
        </PanelBody>

        <PanelBody title={__('Primary Button (Donate Now)', 'mbn-theme')} initialOpen={false}>
          <TextControl
            label={__('Button Text', 'mbn-theme')}
            value={primaryButtonText}
            onChange={(value) => setAttributes({ primaryButtonText: value })}
          />
          <TextControl
            label={__('Button URL', 'mbn-theme')}
            value={primaryButtonUrl}
            onChange={(value) => setAttributes({ primaryButtonUrl: value })}
          />
        </PanelBody>

        <PanelBody title={__('Secondary Button (Learn More)', 'mbn-theme')} initialOpen={false}>
          <TextControl
            label={__('Button Text', 'mbn-theme')}
            value={secondaryButtonText}
            onChange={(value) => setAttributes({ secondaryButtonText: value })}
          />
          <TextControl
            label={__('Button URL', 'mbn-theme')}
            value={secondaryButtonUrl}
            onChange={(value) => setAttributes({ secondaryButtonUrl: value })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Dark Overlay */}
        <div 
          className="absolute inset-0 bg-black pointer-events-none z-10"
          style={{ opacity: overlayOpacity / 100 }}
        />

        {/* Content */}
        <div className="relative z-20 w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 py-20">
          <div className="max-w-2xl">
            
            {/* Subheading (gold) */}
            <RichText
              tagName="p"
              value={subheading}
              onChange={(value) => setAttributes({ subheading: value })}
              placeholder={__('Subheading...', 'mbn-theme')}
              className="font-sofia font-bold text-2xl leading-snug tracking-tight text-gold uppercase"
              allowedFormats={[]}
            />
            
            {/* Main heading (white, large) */}
            <RichText
              tagName="h1"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Main heading...', 'mbn-theme')}
              className="font-sofia font-bold text-7xl leading-none tracking-tight text-white uppercase mb-8 md:mb-10"
              allowedFormats={['core/bold']}
            />
            
            {/* CTA buttons */}
            <div className="flex flex-col sm:flex-row gap-4 md:gap-6">
              
              {/* Donate Now (gold gradient button with arrow) */}
              <div className="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-full font-bold text-base leading-none uppercase tracking-tight bg-gradient-to-b from-gold-light to-gold text-gold-dark shadow-md no-underline">
                <span>{primaryButtonText}</span>
                <span className="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
                  <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 9L9 1M9 1H1M9 1V9" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                </span>
              </div>
              
              {/* Learn More (white outline button) */}
              <div className="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-full font-inter font-semibold text-base leading-normal uppercase bg-cream-light text-gold-dark border border-white no-underline">
                {secondaryButtonText}
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

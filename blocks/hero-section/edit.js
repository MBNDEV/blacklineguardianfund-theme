import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, RangeControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundImageUrl, 
    backgroundImageId, 
    overlayOpacity,
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
        <PanelBody title={__('Background Image', 'blacklineguardianfund-theme')} initialOpen={true}>
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
                      ? __('Replace Background Image', 'blacklineguardianfund-theme') 
                      : __('Select Background Image', 'blacklineguardianfund-theme')
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
        
        <PanelBody title={__('Overlay Settings', 'blacklineguardianfund-theme')} initialOpen={false}>
          <RangeControl
            label={__('Overlay Opacity (%)', 'blacklineguardianfund-theme')}
            value={overlayOpacity}
            onChange={(value) => setAttributes({ overlayOpacity: value })}
            min={0}
            max={100}
            step={5}
          />
        </PanelBody>

        <PanelBody title={__('Primary Button (Donate Now)', 'blacklineguardianfund-theme')} initialOpen={false}>
          <TextControl
            label={__('Button Text', 'blacklineguardianfund-theme')}
            value={primaryButtonText}
            onChange={(value) => setAttributes({ primaryButtonText: value })}
          />
          <TextControl
            label={__('Button URL', 'blacklineguardianfund-theme')}
            value={primaryButtonUrl}
            onChange={(value) => setAttributes({ primaryButtonUrl: value })}
          />
        </PanelBody>

        <PanelBody title={__('Secondary Button (Learn More)', 'blacklineguardianfund-theme')} initialOpen={false}>
          <TextControl
            label={__('Button Text', 'blacklineguardianfund-theme')}
            value={secondaryButtonText}
            onChange={(value) => setAttributes({ secondaryButtonText: value })}
          />
          <TextControl
            label={__('Button URL', 'blacklineguardianfund-theme')}
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
              placeholder={__('Subheading...', 'blacklineguardianfund-theme')}
              className="font-sofia font-bold text-2xl leading-snug tracking-tight text-gold uppercase"
              allowedFormats={[]}
            />
            
            {/* Main heading (white, large) */}
            <RichText
              tagName="h1"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Main heading...', 'blacklineguardianfund-theme')}
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

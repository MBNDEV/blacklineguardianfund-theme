import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, heading, description, backgroundImageUrl, backgroundImageId, overlayOpacity } = attributes;

  const blockProps = useBlockProps({
    className: 'hero-section relative w-full flex items-center',
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
      </InspectorControls>

      <div {...blockProps}>
        {/* Dark Overlay */}
        <div 
          className="absolute inset-0 bg-black pointer-events-none"
          style={{ opacity: overlayOpacity / 100 }}
        />

        {/* Content */}
        <div className="relative z-10 w-full max-w-7xl mx-auto px-4 py-20 lg:py-32">
          <div className="max-w-3xl">
            <RichText
              tagName="p"
              value={eyebrow}
              onChange={(value) => setAttributes({ eyebrow: value })}
              placeholder={__('Eyebrow text...', 'blacklineguardianfund-theme')}
              className="text-amber-600 text-sm font-semibold tracking-wider uppercase mb-4"
              allowedFormats={[]}
            />
            
            <RichText
              tagName="h1"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Enter main heading...', 'blacklineguardianfund-theme')}
              className="text-white text-4xl lg:text-6xl font-bold leading-tight mb-6"
              allowedFormats={['core/bold']}
            />
            
            <RichText
              tagName="p"
              value={description}
              onChange={(value) => setAttributes({ description: value })}
              placeholder={__('Enter description...', 'blacklineguardianfund-theme')}
              className="text-white text-lg leading-relaxed max-w-2xl"
              allowedFormats={[]}
            />
          </div>
        </div>
      </div>
    </>
  );
}

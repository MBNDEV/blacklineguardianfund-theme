import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundImageUrl,
    backgroundImageId,
    topHeading,
    leftText,
    leftImageUrl,
    leftImageId,
    rightText,
    rightImageUrl,
    rightImageId,
    centerIconUrl,
    centerIconId,
    bottomText
  } = attributes;

  const blockProps = useBlockProps({
    className: 'mission-section-editor relative w-full py-20 md:py-32 lg:py-44 overflow-hidden',
    style: backgroundImageUrl ? {
      background: `url(${backgroundImageUrl}) lightgray -653.281px 0px / 238.91% 100% no-repeat`,
      backgroundSize: 'cover',
      backgroundPosition: '100% 30%',
    } : {
      backgroundColor: '#F9F5EE',
    }
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

        <PanelBody title={__('Left Image', 'mbn-theme')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                leftImageUrl: media.url, 
                leftImageId: media.id 
              })}
              allowedTypes={['image']}
              value={leftImageId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {leftImageUrl 
                      ? __('Replace Left Image', 'mbn-theme') 
                      : __('Select Left Image', 'mbn-theme')
                    }
                  </Button>
                  {leftImageUrl && (
                    <div className="mt-4">
                      <img 
                        src={leftImageUrl} 
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

        <PanelBody title={__('Right Image', 'mbn-theme')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                rightImageUrl: media.url, 
                rightImageId: media.id 
              })}
              allowedTypes={['image']}
              value={rightImageId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {rightImageUrl 
                      ? __('Replace Right Image', 'mbn-theme') 
                      : __('Select Right Image', 'mbn-theme')
                    }
                  </Button>
                  {rightImageUrl && (
                    <div className="mt-4">
                      <img 
                        src={rightImageUrl} 
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

        <PanelBody title={__('Center Shield Icon', 'mbn-theme')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                centerIconUrl: media.url, 
                centerIconId: media.id 
              })}
              allowedTypes={['image']}
              value={centerIconId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {centerIconUrl 
                      ? __('Replace Shield Icon', 'mbn-theme') 
                      : __('Select Shield Icon', 'mbn-theme')
                    }
                  </Button>
                  {centerIconUrl && (
                    <div className="mt-4">
                      <img 
                        src={centerIconUrl} 
                        alt="" 
                        className="w-full h-32 object-contain rounded"
                      />
                    </div>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div className="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">
          
          {/* Top Heading */}
          <div className="text-center mb-16 md:mb-24 lg:mb-32">
            <RichText
              tagName="h2"
              value={topHeading}
              onChange={(value) => setAttributes({ topHeading: value })}
              placeholder={__('Enter top heading...', 'mbn-theme')}
              className="text-4xl lg:text-[40px] font-bold font-sofia leading-tight tracking-tight text-mission-text"
              allowedFormats={['core/bold', 'core/italic']}
            />
          </div>

          {/* Content Grid */}
          <div className="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            
            {/* Left Column */}
            <div className="space-y-8 lg:space-y-12">
              {/* Left Text */}
              <div className="text-left max-w-lg lg:mr-16">
                <RichText
                  tagName="p"
                  value={leftText}
                  onChange={(value) => setAttributes({ leftText: value })}
                  placeholder={__('Left column text...', 'mbn-theme')}
                  className="text-mission-text text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia"
                  allowedFormats={[]}
                />
              </div>
              
              {/* Left Image */}
              {leftImageUrl && (
                <div className="relative w-full">
                  <img 
                    src={leftImageUrl} 
                    alt="" 
                    className="w-full h-auto rounded-2xl shadow-xl object-cover"
                  />
                </div>
              )}
            </div>

            {/* Right Column */}
            <div className="space-y-8 lg:space-y-12 relative">
              {/* Right Image */}
              {rightImageUrl && (
                <div className="relative w-full ml-auto">
                  <img 
                    src={rightImageUrl} 
                    alt="" 
                    className="w-full h-auto rounded-2xl shadow-xl object-cover"
                  />
                </div>
              )}
              
              {/* Right Text */}
              <div className="text-left max-w-lg ml-auto">
                <RichText
                  tagName="p"
                  value={rightText}
                  onChange={(value) => setAttributes({ rightText: value })}
                  placeholder={__('Right column text...', 'mbn-theme')}
                  className="text-mission-text text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia"
                  allowedFormats={[]}
                />
              </div>
            </div>

            {/* Center Shield Icon */}
            {centerIconUrl && (
              <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 hidden lg:block pointer-events-none">
                <div className="relative w-48 h-48 lg:w-64 lg:h-64">
                  <img 
                    src={centerIconUrl} 
                    alt="" 
                    className="w-full h-full object-contain drop-shadow-2xl opacity-90"
                  />
                </div>
              </div>
            )}
          </div>

          {/* Bottom Text */}
          <div className="text-center mt-16 md:mt-32 space-y-6">
            <RichText
              tagName="div"
              value={bottomText}
              onChange={(value) => setAttributes({ bottomText: value })}
              placeholder={__('Bottom text...', 'mbn-theme')}
              className="text-mission-text text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia max-w-4xl mx-auto"
              allowedFormats={['core/italic']}
            />
          </div>

        </div>
      </div>
    </>
  );
}

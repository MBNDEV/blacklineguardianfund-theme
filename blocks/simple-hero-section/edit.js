import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, ColorPicker, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundColor,
    backgroundImageUrl, 
    backgroundImageId,
    subtitle,
    heading,
    paragraph,
    email,
    address,
    showPrimaryButton,
    primaryButtonText,
    primaryButtonUrl
  } = attributes;

  const blockProps = useBlockProps({
    className: 'simple-hero-section-editor relative w-full py-20',
    style: {
      backgroundColor: backgroundColor,
      backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      minHeight: '400px',
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Background Settings', 'mbn-theme')} initialOpen={true}>
          <div className="mb-4">
            <label className="block mb-2 font-medium">
              {__('Background Color', 'mbn-theme')}
            </label>
            <ColorPicker
              color={backgroundColor}
              onChangeComplete={(value) => setAttributes({ backgroundColor: value.hex })}
              disableAlpha={false}
            />
          </div>
          
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
                    <>
                      <div className="mt-4">
                        <img 
                          src={backgroundImageUrl} 
                          alt="" 
                          className="w-full h-32 object-cover rounded"
                        />
                      </div>
                      <Button 
                        onClick={() => setAttributes({ 
                          backgroundImageUrl: '', 
                          backgroundImageId: 0 
                        })} 
                        variant="secondary"
                        className="mt-2"
                        isDestructive
                      >
                        {__('Remove Image', 'mbn-theme')}
                      </Button>
                    </>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>

        <PanelBody title={__('Content', 'mbn-theme')} initialOpen={false}>
          <TextControl
            label={__('Subtitle', 'mbn-theme')}
            value={subtitle}
            onChange={(value) => setAttributes({ subtitle: value })}
            help={__('Small text above the heading', 'mbn-theme')}
          />
          <TextControl
            label={__('Heading', 'mbn-theme')}
            value={heading}
            onChange={(value) => setAttributes({ heading: value })}
          />
          <TextControl
            label={__('Paragraph', 'mbn-theme')}
            value={paragraph}
            onChange={(value) => setAttributes({ paragraph: value })}
            help={__('Description text', 'mbn-theme')}
          />
        </PanelBody>

        <PanelBody title={__('Contact Information', 'mbn-theme')} initialOpen={false}>
          <TextControl
            label={__('Email', 'mbn-theme')}
            type="email"
            value={email}
            onChange={(value) => setAttributes({ email: value })}
          />
          <TextControl
            label={__('Address', 'mbn-theme')}
            value={address}
            onChange={(value) => setAttributes({ address: value })}
          />
        </PanelBody>

        <PanelBody title={__('CTA Button', 'mbn-theme')} initialOpen={false}>
          <ToggleControl
            label={__('Show CTA Button', 'mbn-theme')}
            checked={showPrimaryButton}
            onChange={(value) => setAttributes({ showPrimaryButton: value })}
          />
          {showPrimaryButton && (
            <>
              <TextControl
                label={__('Button Text', 'mbn-theme')}
                value={primaryButtonText}
                onChange={(value) => setAttributes({ primaryButtonText: value })}
              />
              <TextControl
                label={__('Button URL', 'mbn-theme')}
                value={primaryButtonUrl}
                onChange={(value) => setAttributes({ primaryButtonUrl: value })}
                placeholder="https://"
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {/* Blur Overlay */}
        <div className="absolute left-1/2 -translate-x-1/2 pointer-events-none -bottom-[567px] w-[1354px] h-[647px] rounded-[1354px] bg-blue-200 opacity-40 blur-[130px]"></div>
        
        <div className="relative z-10 container mx-auto px-6 md:px-12 lg:px-16 max-w-6xl">
          <div className="text-center">
            {/* Subtitle */}
            <RichText
              tagName="p"
              value={subtitle}
              onChange={(value) => setAttributes({ subtitle: value })}
              placeholder={__('Enter subtitle...', 'mbn-theme')}
              className="font-sofia text-gold text-lg md:text-2xl font-bold tracking-[0.2em] uppercase"
            />

            {/* Heading */}
            <RichText
              tagName="h1"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Enter heading...', 'mbn-theme')}
              className="font-sofia text-4xl sm:text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight text-white mb-6"
            />

            {/* Paragraph */}
            <RichText
              tagName="p"
              value={paragraph}
              onChange={(value) => setAttributes({ paragraph: value })}
              placeholder={__('Enter description...', 'mbn-theme')}
              className="font-inter text-white font-light text-base md:text-lg leading-relaxed max-w-4xl mx-auto mb-6"
            />

            {/* Contact Info */}
            <div className="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8 lg:gap-16">
              {/* Email */}
              <div className="flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" className="w-12" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M3.55286 20.1497C3.09286 20.1497 2.69403 19.9808 2.35636 19.643C2.01853 19.3053 1.84961 18.9065 1.84961 18.4465V5.55398C1.84961 5.09231 2.01853 4.69206 2.35636 4.35323C2.69403 4.01423 3.09286 3.84473 3.55286 3.84473H20.4454C20.907 3.84473 21.3073 4.01423 21.6461 4.35323C21.9851 4.69206 22.1546 5.09231 22.1546 5.55398V18.4465C22.1546 18.9065 21.9851 19.3053 21.6461 19.643C21.3073 19.9808 20.907 20.1497 20.4454 20.1497H3.55286ZM20.4454 7.06648L12.4594 12.3245C12.3812 12.366 12.3078 12.4002 12.2391 12.4272C12.1704 12.4542 12.0904 12.4677 11.9991 12.4677C11.9078 12.4677 11.8278 12.4542 11.7591 12.4272C11.6904 12.4002 11.617 12.366 11.5389 12.3245L3.55286 7.06648V18.4465H20.4454V7.06648ZM11.9991 11.004L20.3454 5.55398H3.67786L11.9991 11.004ZM3.55286 7.25948V6.18397V6.20423V5.55398V6.19473V6.16598V7.25948Z" fill="white" fillOpacity="0.6"/>
                </svg>
                <RichText
                  tagName="a"
                  value={email}
                  onChange={(value) => setAttributes({ email: value })}
                  placeholder={__('Enter email...', 'mbn-theme')}
                  className="font-inter text-white/60 text-base md:text-lg underline"
                />
              </div>

              {/* Address */}
              <div className="flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" className="w-12" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M11.9991 19.9057C14.1958 17.9129 15.8194 16.1146 16.8699 14.5107C17.9202 12.9071 18.4454 11.4642 18.4454 10.1822C18.4454 8.23923 17.8229 6.64873 16.5779 5.41073C15.3327 4.17289 13.8064 3.55398 11.9989 3.55398C10.1914 3.55398 8.66519 4.17289 7.42036 5.41073C6.17536 6.64873 5.55286 8.23923 5.55286 10.1822C5.55286 11.4642 6.09053 12.9061 7.16586 14.5077C8.24136 16.1096 9.85244 17.9089 11.9991 19.9057ZM11.9914 21.6257C11.8052 21.6257 11.6184 21.5946 11.4311 21.5322C11.2438 21.4699 11.0799 21.3701 10.9394 21.2327C10.2154 20.5916 9.43911 19.8565 8.61061 19.0275C7.78194 18.1985 7.01461 17.306 6.30861 16.35C5.60261 15.394 5.01628 14.3923 4.54961 13.345C4.08294 12.2978 3.84961 11.2436 3.84961 10.1822C3.84961 7.64356 4.66928 5.61931 6.30861 4.10948C7.94778 2.59964 9.84461 1.84473 11.9991 1.84473C14.1536 1.84473 16.0514 2.59964 17.6926 4.10948C19.3339 5.61931 20.1546 7.64356 20.1546 10.1822C20.1546 11.2436 19.9203 12.2978 19.4516 13.345C18.9829 14.3923 18.3956 15.394 17.6896 16.35C16.9836 17.306 16.2163 18.1985 15.3876 19.0275C14.5591 19.8565 13.7849 20.5916 13.0649 21.2327C12.9197 21.3701 12.751 21.4699 12.5589 21.5322C12.3667 21.5946 12.1775 21.6257 11.9914 21.6257Z" stroke="white" fill="white" fillOpacity="0.6"/>
                </svg>
                <RichText
                  tagName="p"
                  value={address}
                  onChange={(value) => setAttributes({ address: value })}
                  placeholder={__('Enter address...', 'mbn-theme')}
                  className="font-inter text-white/60 text-base md:text-lg"
                />
              </div>
            </div>

            {/* CTA Button Preview */}
            {showPrimaryButton && primaryButtonText && (
              <div className="mt-8">
                <a 
                  href={primaryButtonUrl || '#'}
                  className="inline-flex font-inter items-center justify-center gap-2 h-11 px-5 rounded-full font-bold text-base leading-none uppercase tracking-tight transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark shadow-md hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline"
                  onClick={(e) => e.preventDefault()}
                >
                  <span className="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" className="scale-x-[-1]" width="9" height="9" viewBox="0 0 9 9" fill="none" aria-hidden="true" focusable="false">
                      <path d="M0.000102425 8.15588L7.13312 1.02286L2.3107 1.02357L2.31105 -1.78562e-07L8.35508 -1.36862e-08L8.85496 0.0113766L8.86634 0.511254L8.86634 6.55528L7.84383 6.55528L7.84418 1.73392L0.711165 8.86695L0.000102425 8.15588Z" fill="white"/>
                    </svg>
                  </span>
                  <span>{primaryButtonText}</span>
                </a>
              </div>
            )}
          </div>
        </div>
      </div>
    </>
  );
}

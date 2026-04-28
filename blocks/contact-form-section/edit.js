import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundImageUrl, 
    backgroundImageId,
    formShortcode,
    footerNote
  } = attributes;

  const blockProps = useBlockProps({
    className: 'contact-form-section-editor relative w-full py-12 px-6',
    style: {
      backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      backgroundColor: '#F9F5EE',
      minHeight: '600px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
    },
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Background Settings', 'mbn-theme')} initialOpen={true}>
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
                          style={{ width: '100%', height: '128px', objectFit: 'cover', borderRadius: '4px' }}
                        />
                      </div>
                      <Button 
                        onClick={() => setAttributes({ 
                          backgroundImageUrl: '', 
                          backgroundImageId: 0 
                        })} 
                        variant="secondary"
                        style={{ marginTop: '8px' }}
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

        <PanelBody title={__('Form Settings', 'mbn-theme')} initialOpen={true}>
          <TextControl
            label={__('Gravity Forms Shortcode', 'mbn-theme')}
            value={formShortcode}
            onChange={(value) => setAttributes({ formShortcode: value })}
            help={__('Enter your Gravity Form shortcode (e.g., [gravityform id="1" title="false" description="false"])', 'mbn-theme')}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div style={{ width: '100%', maxWidth: '672px' }}>
          {/* Contact Form Card */}
          <div 
            className="contact-form-card"
            style={{
              border: '1px solid rgba(0, 0, 0, 0.15)',
              background: 'linear-gradient(124deg, rgba(211, 233, 255, 0.96) -22.21%, rgba(243, 236, 222, 0.96) 82.87%)',
              boxShadow: '0 2px 60px 0 rgba(0, 0, 0, 0.08)',
              borderRadius: '1rem',
              padding: '2.5rem',
            }}
          >
            {/* Form Preview */}
            <div style={{ marginBottom: '1.5rem' }}>
              <div 
                style={{
                  padding: '1rem',
                  backgroundColor: 'rgba(255, 255, 255, 0.7)',
                  borderRadius: '0.5rem',
                  border: '2px dashed #D1D5DB',
                  textAlign: 'center',
                  fontSize: '0.875rem',
                  color: '#6B7280',
                }}
              >
                <strong>{__('Gravity Form Preview', 'mbn-theme')}</strong>
                <br />
                <code style={{ fontSize: '0.75rem', marginTop: '0.5rem', display: 'block' }}>
                  {formShortcode}
                </code>
                <p style={{ marginTop: '0.5rem', fontSize: '0.75rem' }}>
                  {__('The actual form will be rendered on the frontend', 'mbn-theme')}
                </p>
              </div>
            </div>

            {/* Footer Note */}
            <RichText
              tagName="div"
              value={footerNote}
              onChange={(value) => setAttributes({ footerNote: value })}
              placeholder={__('Enter footer note...', 'mbn-theme')}
              style={{
                textAlign: 'center',
                fontSize: '0.75rem',
                color: '#6B7280',
                lineHeight: '1.5',
                marginTop: '1.5rem',
              }}
            />
          </div>
        </div>
      </div>
    </>
  );
}

import { useBlockProps, InspectorControls, MediaUpload, RichText } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, TextareaControl, IconButton, Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

// Generate unique ID
const generateUniqueId = () => {
  return `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
};

export default function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageUrl,
    backgroundImageId,
    row1Col1Heading,
    row1Col1Paragraph,
    row1Col1IconList = [],
    row1Col2GravityFormShortcode,
    floatingShieldIconUrl,
    floatingShieldIconId,
    floatingShieldIconAlt,
    formFooterText,
    row2Col1Heading,
    row2Col1Paragraph,
    row2Col1Programs = [],
    row2Col2Heading,
    row2Col2Paragraph1,
    row2Col2BulletList = [],
    row2Col2Paragraph2
  } = attributes;

  // Icon List Functions (Row 1 Col 1)
  const updateIconItem = (index, updates) => {
    const updated = [...row1Col1IconList];
    updated[index] = { ...updated[index], ...updates };
    setAttributes({ row1Col1IconList: updated });
  };

  const addIconItem = () => {
    setAttributes({
      row1Col1IconList: [...row1Col1IconList, {
        id: generateUniqueId(),
        iconUrl: '',
        iconId: 0,
        text: ''
      }]
    });
  };

  const removeIconItem = (index) => {
    setAttributes({
      row1Col1IconList: row1Col1IconList.filter((_, i) => i !== index)
    });
  };

  // Programs List Functions
  const updateProgram = (index, updates) => {
    const updated = [...row2Col1Programs];
    updated[index] = { ...updated[index], ...updates };
    setAttributes({ row2Col1Programs: updated });
  };

  const addProgram = () => {
    setAttributes({
      row2Col1Programs: [...row2Col1Programs, {
        id: generateUniqueId(),
        iconUrl: '',
        iconId: 0,
        heading: '',
        text: ''
      }]
    });
  };

  const removeProgram = (index) => {
    setAttributes({
      row2Col1Programs: row2Col1Programs.filter((_, i) => i !== index)
    });
  };

  // Bullet List Functions
  const updateBullet = (index, text) => {
    const updated = [...row2Col2BulletList];
    updated[index] = { ...updated[index], text };
    setAttributes({ row2Col2BulletList: updated });
  };

  const addBullet = () => {
    setAttributes({
      row2Col2BulletList: [...row2Col2BulletList, {
        id: generateUniqueId(),
        text: ''
      }]
    });
  };

  const removeBullet = (index) => {
    setAttributes({
      row2Col2BulletList: row2Col2BulletList.filter((_, i) => i !== index)
    });
  };

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Background', 'mbn-theme')} initialOpen={false}>
          <MediaUpload
            onSelect={(media) => setAttributes({ backgroundImageUrl: media.url, backgroundImageId: media.id })}
            allowedTypes={['image']}
            value={backgroundImageId}
            render={({ open }) => (
              <div>
                <Button onClick={open} variant="primary">
                  {backgroundImageUrl ? __('Replace Background', 'mbn-theme') : __('Select Background', 'mbn-theme')}
                </Button>
                {backgroundImageUrl && (
                  <img src={backgroundImageUrl} alt="" style={{ marginTop: '10px', maxWidth: '100%' }} />
                )}
              </div>
            )}
          />
        </PanelBody>

        <PanelBody title={__('Row 1 - Column 1 (Text)', 'mbn-theme')} initialOpen={true}>
          <TextControl
            label={__('Heading (H2)', 'mbn-theme')}
            value={row1Col1Heading}
            onChange={(value) => setAttributes({ row1Col1Heading: value })}
          />
          <TextareaControl
            label={__('Paragraph', 'mbn-theme')}
            value={row1Col1Paragraph}
            onChange={(value) => setAttributes({ row1Col1Paragraph: value })}
            rows={4}
          />

          <hr style={{ margin: '20px 0' }} />
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <strong>{__('Icon List', 'mbn-theme')}</strong>
            <Button variant="secondary" onClick={addIconItem} isSmall>
              +
            </Button>
          </div>

          {row1Col1IconList.map((item, index) => (
            <div key={item.id} style={{ marginTop: '15px', padding: '15px', border: '1px solid #ddd', borderRadius: '4px' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                <strong>{__('Item', 'mbn-theme')} {index + 1}</strong>
                <IconButton
                  icon="trash"
                  label={__('Remove', 'mbn-theme')}
                  onClick={() => removeIconItem(index)}
                />
              </div>

              <MediaUpload
                onSelect={(media) => updateIconItem(index, { iconUrl: media.url, iconId: media.id })}
                allowedTypes={['image']}
                value={item.iconId}
                render={({ open }) => (
                  <div>
                    <Button onClick={open} variant="secondary" size="small">
                      {item.iconUrl ? __('Replace Icon', 'mbn-theme') : __('Select Icon', 'mbn-theme')}
                    </Button>
                    {item.iconUrl && (
                      <img src={item.iconUrl} alt="" style={{ marginTop: '10px', maxWidth: '50px' }} />
                    )}
                  </div>
                )}
              />

              <TextareaControl
                label={__('Text', 'mbn-theme')}
                value={item.text}
                onChange={(value) => updateIconItem(index, { text: value })}
                rows={2}
              />
            </div>
          ))}
        </PanelBody>

        <PanelBody title={__('Row 1 - Column 2 (Form)', 'mbn-theme')} initialOpen={true}>
          <TextControl
            label={__('Gravity Form Shortcode', 'mbn-theme')}
            value={row1Col2GravityFormShortcode}
            onChange={(value) => setAttributes({ row1Col2GravityFormShortcode: value })}
            placeholder="[gravityform id=1 title=false description=false]"
          />
          
          <TextareaControl
            label={__('Form Footer Text (HTML allowed)', 'mbn-theme')}
            value={formFooterText}
            onChange={(value) => setAttributes({ formFooterText: value })}
            rows={3}
            help={__('You can use HTML tags like <a href="/url">text</a>', 'mbn-theme')}
          />
          
          <hr style={{ margin: '20px 0' }} />
          <p style={{ marginBottom: '10px', fontWeight: '500' }}>{__('Floating Shield Icon', 'mbn-theme')}</p>
          <MediaUpload
            onSelect={(media) => setAttributes({ floatingShieldIconUrl: media.url, floatingShieldIconId: media.id })}
            allowedTypes={['image']}
            value={floatingShieldIconId}
            render={({ open }) => (
              <div>
                <Button onClick={open} variant="secondary">
                  {floatingShieldIconUrl ? __('Replace Shield Icon', 'mbn-theme') : __('Select Shield Icon', 'mbn-theme')}
                </Button>
                {floatingShieldIconUrl && (
                  <img src={floatingShieldIconUrl} alt="" style={{ marginTop: '10px', maxWidth: '100px' }} />
                )}
              </div>
            )}
          />
          <TextControl
            label={__('Shield Icon Alt Text', 'mbn-theme')}
            value={floatingShieldIconAlt}
            onChange={(value) => setAttributes({ floatingShieldIconAlt: value })}
            help={__('Descriptive text for screen readers', 'mbn-theme')}
          />
        </PanelBody>

        <PanelBody title={__('Row 2 - Column 1', 'mbn-theme')} initialOpen={true}>
          <TextControl
            label={__('Heading (H2)', 'mbn-theme')}
            value={row2Col1Heading}
            onChange={(value) => setAttributes({ row2Col1Heading: value })}
          />
          <TextareaControl
            label={__('Paragraph', 'mbn-theme')}
            value={row2Col1Paragraph}
            onChange={(value) => setAttributes({ row2Col1Paragraph: value })}
            rows={3}
          />

          <hr style={{ margin: '20px 0' }} />
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <strong>{__('Programs List', 'mbn-theme')}</strong>
            <Button variant="secondary" onClick={addProgram} isSmall>
              +
            </Button>
          </div>

          {row2Col1Programs.map((program, index) => (
            <div key={program.id} style={{ marginTop: '15px', padding: '15px', border: '1px solid #ddd', borderRadius: '4px' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                <strong>{__('Program', 'mbn-theme')} {index + 1}</strong>
                <IconButton
                  icon="trash"
                  label={__('Remove', 'mbn-theme')}
                  onClick={() => removeProgram(index)}
                />
              </div>

              <MediaUpload
                onSelect={(media) => updateProgram(index, { iconUrl: media.url, iconId: media.id })}
                allowedTypes={['image']}
                value={program.iconId}
                render={({ open }) => (
                  <div>
                    <Button onClick={open} variant="secondary" size="small">
                      {program.iconUrl ? __('Replace Icon', 'mbn-theme') : __('Select Icon', 'mbn-theme')}
                    </Button>
                    {program.iconUrl && (
                      <img src={program.iconUrl} alt="" style={{ marginTop: '10px', maxWidth: '50px' }} />
                    )}
                  </div>
                )}
              />

              <TextControl
                label={__('Heading (H4)', 'mbn-theme')}
                value={program.heading}
                onChange={(value) => updateProgram(index, { heading: value })}
              />
              <TextareaControl
                label={__('Description', 'mbn-theme')}
                value={program.text}
                onChange={(value) => updateProgram(index, { text: value })}
                rows={3}
              />
            </div>
          ))}
        </PanelBody>

        <PanelBody title={__('Row 2 - Column 2', 'mbn-theme')} initialOpen={true}>
          <TextControl
            label={__('Heading (H2)', 'mbn-theme')}
            value={row2Col2Heading}
            onChange={(value) => setAttributes({ row2Col2Heading: value })}
          />
          <TextareaControl
            label={__('Paragraph 1', 'mbn-theme')}
            value={row2Col2Paragraph1}
            onChange={(value) => setAttributes({ row2Col2Paragraph1: value })}
            rows={3}
          />

          <hr style={{ margin: '20px 0' }} />
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <strong>{__('Bullet List', 'mbn-theme')}</strong>
            <Button variant="secondary" onClick={addBullet} isSmall>
              +
            </Button>
          </div>

          {row2Col2BulletList.map((bullet, index) => (
            <div key={bullet.id} style={{ marginTop: '10px', display: 'flex', gap: '10px', alignItems: 'flex-start' }}>
              <TextareaControl
                value={bullet.text}
                onChange={(value) => updateBullet(index, value)}
                rows={2}
                style={{ flex: 1 }}
              />
              <IconButton
                icon="trash"
                label={__('Remove', 'mbn-theme')}
                onClick={() => removeBullet(index)}
              />
            </div>
          ))}

          <TextareaControl
            label={__('Paragraph 2', 'mbn-theme')}
            value={row2Col2Paragraph2}
            onChange={(value) => setAttributes({ row2Col2Paragraph2: value })}
            rows={3}
            style={{ marginTop: '20px' }}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'alignfull' })}>
        <div style={{
          padding: '40px 20px',
          backgroundColor: '#F9F5EE',
          backgroundImage: backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          minHeight: '400px'
        }}>
          <div style={{ maxWidth: '1280px', margin: '0 auto' }}>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '40px', marginBottom: '40px' }}>
              {/* Row 1 Col 1 Preview */}
              <div>
                <RichText
                  tagName="h2"
                  value={row1Col1Heading}
                  onChange={(value) => setAttributes({ row1Col1Heading: value })}
                  placeholder={__('Enter heading...', 'mbn-theme')}
                  style={{ fontSize: '28px', fontWeight: 'bold', marginBottom: '16px', color: '#000' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color']}
                />
                <RichText
                  tagName="p"
                  value={row1Col1Paragraph}
                  onChange={(value) => setAttributes({ row1Col1Paragraph: value })}
                  placeholder={__('Enter paragraph...', 'mbn-theme')}
                  style={{ fontSize: '16px', color: '#000', marginBottom: '16px', lineHeight: '1.6' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                />
                {row1Col1IconList.length > 0 && (
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                    {row1Col1IconList.map((item, index) => (
                      <div key={index} style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
                        {item.iconUrl && (
                          <div style={{ width: '32px', height: '32px', flexShrink: 0 }}>
                            <img src={item.iconUrl} alt="" style={{ width: '100%', height: '100%' }} />
                          </div>
                        )}
                        <RichText
                          tagName="p"
                          value={item.text}
                          onChange={(value) => updateIconItem(index, { text: value })}
                          placeholder={__('Enter text...', 'mbn-theme')}
                          style={{ fontSize: '14px', color: '#000', fontWeight: 'bold', margin: 0 }}
                          allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                        />
                      </div>
                    ))}
                  </div>
                )}
              </div>
              
              {/* Row 1 Col 2 Preview */}
              <div style={{ backgroundColor: 'rgba(255,255,255,0.5)', padding: '20px', borderRadius: '8px' }}>
                {row1Col2GravityFormShortcode ? (
                  <p style={{ fontSize: '14px', color: '#666', fontStyle: 'italic' }}>
                    {__('Gravity Form:', 'mbn-theme')} {row1Col2GravityFormShortcode}
                  </p>
                ) : (
                  <p style={{ fontSize: '14px', color: '#999' }}>
                    {__('No form shortcode set', 'mbn-theme')}
                  </p>
                )}
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '40px' }}>
              {/* Row 2 Col 1 Preview */}
              <div>
                <RichText
                  tagName="h2"
                  value={row2Col1Heading}
                  onChange={(value) => setAttributes({ row2Col1Heading: value })}
                  placeholder={__('Enter heading...', 'mbn-theme')}
                  style={{ fontSize: '28px', fontWeight: 'bold', marginBottom: '16px', color: '#000' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color']}
                />
                <RichText
                  tagName="p"
                  value={row2Col1Paragraph}
                  onChange={(value) => setAttributes({ row2Col1Paragraph: value })}
                  placeholder={__('Enter paragraph...', 'mbn-theme')}
                  style={{ fontSize: '16px', color: '#000', marginBottom: '16px', lineHeight: '1.6' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                />
                {row2Col1Programs.length > 0 && (
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                    {row2Col1Programs.map((program, index) => (
                      <div key={index} style={{ display: 'flex', gap: '12px' }}>
                        {program.iconUrl && (
                          <div style={{ width: '32px', height: '32px', flexShrink: 0 }}>
                            <img src={program.iconUrl} alt="" style={{ width: '100%', height: '100%' }} />
                          </div>
                        )}
                        <div>
                          <RichText
                            tagName="h4"
                            value={program.heading}
                            onChange={(value) => updateProgram(index, { heading: value })}
                            placeholder={__('Enter heading...', 'mbn-theme')}
                            style={{ fontSize: '18px', fontWeight: 'bold', marginBottom: '4px', color: '#000' }}
                            allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color']}
                          />
                          <RichText
                            tagName="p"
                            value={program.text}
                            onChange={(value) => updateProgram(index, { text: value })}
                            placeholder={__('Enter description...', 'mbn-theme')}
                            style={{ fontSize: '14px', color: '#666', lineHeight: '1.5', margin: 0 }}
                            allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </div>

              {/* Row 2 Col 2 Preview */}
              <div>
                <RichText
                  tagName="h2"
                  value={row2Col2Heading}
                  onChange={(value) => setAttributes({ row2Col2Heading: value })}
                  placeholder={__('Enter heading...', 'mbn-theme')}
                  style={{ fontSize: '28px', fontWeight: 'bold', marginBottom: '16px', color: '#000' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color']}
                />
                <RichText
                  tagName="p"
                  value={row2Col2Paragraph1}
                  onChange={(value) => setAttributes({ row2Col2Paragraph1: value })}
                  placeholder={__('Enter paragraph...', 'mbn-theme')}
                  style={{ fontSize: '16px', color: '#000', marginBottom: '16px', lineHeight: '1.6' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                />
                {row2Col2BulletList.length > 0 && (
                  <ul style={{ listStyleType: 'disc', paddingLeft: '20px', marginBottom: '16px' }}>
                    {row2Col2BulletList.map((bullet, index) => (
                      <li key={index} style={{ fontSize: '14px', color: '#000', marginBottom: '8px' }}>
                        <RichText
                          tagName="span"
                          value={bullet.text}
                          onChange={(value) => updateBullet(index, value)}
                          placeholder={__('Enter bullet point...', 'mbn-theme')}
                          style={{ display: 'inline' }}
                          allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                        />
                      </li>
                    ))}
                  </ul>
                )}
                <RichText
                  tagName="p"
                  value={row2Col2Paragraph2}
                  onChange={(value) => setAttributes({ row2Col2Paragraph2: value })}
                  placeholder={__('Enter paragraph...', 'mbn-theme')}
                  style={{ fontSize: '16px', color: '#000', lineHeight: '1.6' }}
                  allowedFormats={['core/bold', 'core/italic', 'core/underline', 'core/text-color', 'core/link']}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </Fragment>
  );
}

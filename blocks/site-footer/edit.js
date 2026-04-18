import { useBlockProps, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Button, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';

export default function Edit({ attributes, setAttributes }) {
  const {
    logoUrl,
    logoId,
    footerText,
    email,
    emailUrl,
    showEmailIcon,
    emailIconUrl,
    emailIconId,
    socialLinks,
    menuId1,
    menuId2,
    legalInfo,
    legalLinks
  } = attributes;

  const blockProps = useBlockProps({
    className: 'w-full bg-footer-bg text-white'
  });

  // Get available menus
  const menus = useSelect((select) => {
    return select('core').getEntityRecords('taxonomy', 'nav_menu', { per_page: -1 });
  }, []);

  const menuOptions = [
    { label: __('— Select Menu —', 'blacklineguardianfund-theme'), value: 0 },
    ...(menus || []).map((menu) => ({
      label: menu.name,
      value: menu.id
    }))
  ];

  const updateSocialLink = (index, key, value) => {
    const updatedLinks = [...socialLinks];
    updatedLinks[index] = { ...updatedLinks[index], [key]: value };
    setAttributes({ socialLinks: updatedLinks });
  };

  const updateLegalLink = (index, key, value) => {
    const updatedLinks = [...legalLinks];
    updatedLinks[index] = { ...updatedLinks[index], [key]: value };
    setAttributes({ legalLinks: updatedLinks });
  };

  const addLegalLink = () => {
    setAttributes({
      legalLinks: [...legalLinks, { text: '', url: '' }]
    });
  };

  const removeLegalLink = (index) => {
    const updatedLinks = legalLinks.filter((_, i) => i !== index);
    setAttributes({ legalLinks: updatedLinks });
  };

  const socialIcons = {
    facebook: <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.413c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.971h-1.513c-1.491 0-1.956.93-1.956 1.886v2.265h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>,
    instagram: <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>,
    x: <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>,
    linkedin: <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>,
    youtube: <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Logo', 'blacklineguardianfund-theme')}>
          <MediaUpload
            onSelect={(media) => setAttributes({ logoUrl: media.url, logoId: media.id })}
            allowedTypes={['image']}
            value={logoId}
            render={({ open }) => (
              <div>
                <Button onClick={open} variant="primary">
                  {logoUrl ? __('Replace Logo', 'blacklineguardianfund-theme') : __('Select Logo', 'blacklineguardianfund-theme')}
                </Button>
                {logoUrl && (
                  <div style={{ marginTop: '10px' }}>
                    <img src={logoUrl} alt="" style={{ maxWidth: '150px' }} />
                  </div>
                )}
              </div>
            )}
          />
        </PanelBody>

        <PanelBody title={__('Email', 'blacklineguardianfund-theme')}>
          <TextControl
            label={__('Email Address', 'blacklineguardianfund-theme')}
            value={email}
            onChange={(value) => setAttributes({ email: value })}
          />
          <TextControl
            label={__('Email URL', 'blacklineguardianfund-theme')}
            value={emailUrl}
            onChange={(value) => setAttributes({ emailUrl: value })}
            help={__('e.g., mailto:info@example.com', 'blacklineguardianfund-theme')}
          />
          <ToggleControl
            label={__('Show Email Icon', 'blacklineguardianfund-theme')}
            checked={showEmailIcon}
            onChange={(value) => setAttributes({ showEmailIcon: value })}
          />
          {showEmailIcon && (
            <MediaUpload
              onSelect={(media) => setAttributes({ emailIconUrl: media.url, emailIconId: media.id })}
              allowedTypes={['image']}
              value={emailIconId}
              render={({ open }) => (
                <div style={{ marginTop: '10px' }}>
                  <Button onClick={open} variant="secondary">
                    {emailIconUrl ? __('Replace Email Icon', 'blacklineguardianfund-theme') : __('Upload Email Icon', 'blacklineguardianfund-theme')}
                  </Button>
                  {emailIconUrl && (
                    <div style={{ marginTop: '10px' }}>
                      <img src={emailIconUrl} alt="" style={{ maxWidth: '20px', height: 'auto' }} />
                    </div>
                  )}
                </div>
              )}
            />
          )}
        </PanelBody>

        <PanelBody title={__('Social Media Links', 'blacklineguardianfund-theme')} initialOpen={false}>
          {socialLinks.map((link, index) => (
            <div key={index} style={{ marginBottom: '20px', padding: '10px', border: '1px solid #ddd' }}>
              <strong>{link.platform.charAt(0).toUpperCase() + link.platform.slice(1)}</strong>
              <TextControl
                label={__('URL', 'blacklineguardianfund-theme')}
                value={link.url}
                onChange={(value) => updateSocialLink(index, 'url', value)}
              />
              <ToggleControl
                label={__('Open in New Tab', 'blacklineguardianfund-theme')}
                checked={link.openInNewTab}
                onChange={(value) => updateSocialLink(index, 'openInNewTab', value)}
              />
            </div>
          ))}
        </PanelBody>

        <PanelBody title={__('Footer Menus', 'blacklineguardianfund-theme')}>
          <SelectControl
            label={__('Menu 1 (Column 1)', 'blacklineguardianfund-theme')}
            value={menuId1}
            options={menuOptions}
            onChange={(value) => setAttributes({ menuId1: parseInt(value) })}
          />
          <SelectControl
            label={__('Menu 2 (Column 2)', 'blacklineguardianfund-theme')}
            value={menuId2}
            options={menuOptions}
            onChange={(value) => setAttributes({ menuId2: parseInt(value) })}
          />
        </PanelBody>

        <PanelBody title={__('Legal Links', 'blacklineguardianfund-theme')} initialOpen={false}>
          {legalLinks.map((link, index) => (
            <div key={index} style={{ marginBottom: '15px', padding: '10px', border: '1px solid #ddd' }}>
              <TextControl
                label={__('Link Text', 'blacklineguardianfund-theme')}
                value={link.text}
                onChange={(value) => updateLegalLink(index, 'text', value)}
              />
              <TextControl
                label={__('Link URL', 'blacklineguardianfund-theme')}
                value={link.url}
                onChange={(value) => updateLegalLink(index, 'url', value)}
              />
              <Button
                isDestructive
                variant="secondary"
                onClick={() => removeLegalLink(index)}
                style={{ marginTop: '5px' }}
              >
                {__('Remove', 'blacklineguardianfund-theme')}
              </Button>
            </div>
          ))}
          <Button variant="primary" onClick={addLegalLink}>
            {__('Add Legal Link', 'blacklineguardianfund-theme')}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps} style={{ backgroundColor: '#191919', color: 'white', padding: '48px 24px' }}>
        
        {/* Main Footer Content */}
        <div style={{ maxWidth: '1536px', margin: '0 auto' }}>
          
          {/* Grid Layout */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '48px' }}>
            
            {/* Left Column */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
              
              {/* Logo */}
              {logoUrl && (
                <div>
                  <img src={logoUrl} alt="Logo" style={{ maxWidth: '150px', height: 'auto' }} />
                </div>
              )}
              
              {/* Footer Text */}
              <RichText
                tagName="p"
                value={footerText}
                onChange={(value) => setAttributes({ footerText: value })}
                placeholder={__('Enter footer mission statement...', 'blacklineguardianfund-theme')}
                style={{ fontSize: '16px', lineHeight: '1.625', color: '#B2B2B2', maxWidth: '448px' }}
              />
              
              {/* Email */}
              <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                {showEmailIcon && (
                  emailIconUrl ? (
                    <img src={emailIconUrl} alt="" style={{ width: '20px', height: 'auto' }} />
                  ) : (
                    <svg width="20" height="16" viewBox="0 0 20 16" fill="none">
                      <path d="M2 0C0.9 0 0.00999999 0.9 0.00999999 2L0 14C0 15.1 0.9 16 2 16H18C19.1 16 20 15.1 20 14V2C20 0.9 19.1 0 18 0H2ZM18 4L10 9L2 4V2L10 7L18 2V4Z" fill="#B89352"/>
                    </svg>
                  )
                )}
                <span style={{ fontSize: '16px', color: 'white' }}>{email}</span>
              </div>
              
              {/* Social Media Icons */}
              <div style={{ display: 'flex', alignItems: 'center', gap: '16px', paddingTop: '16px' }}>
                {socialLinks.map((link, index) => (
                  <a
                    key={index}
                    href={link.url}
                    target={link.openInNewTab ? '_blank' : '_self'}
                    rel={link.openInNewTab ? 'noopener noreferrer' : ''}
                    style={{ color: '#B89352', textDecoration: 'none' }}
                    aria-label={link.platform}
                  >
                    {socialIcons[link.platform]}
                  </a>
                ))}
              </div>
              
            </div>
            
            {/* Right Column - Menus Preview */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '32px' }}>
              <div>
                <p style={{ fontSize: '14px', color: '#B89352', marginBottom: '8px' }}>
                  {menuId1 ? `Menu ${menuId1} Selected` : __('Select Menu 1', 'blacklineguardianfund-theme')}
                </p>
              </div>
              <div>
                <p style={{ fontSize: '14px', color: '#B89352', marginBottom: '8px' }}>
                  {menuId2 ? `Menu ${menuId2} Selected` : __('Select Menu 2', 'blacklineguardianfund-theme')}
                </p>
              </div>
            </div>
            
          </div>
          
        </div>
        
        {/* Divider */}
        <div style={{ height: '1px', backgroundColor: '#B89352', margin: '48px auto', maxWidth: '1536px' }}></div>
        
        {/* Bottom Legal Section */}
        <div style={{ maxWidth: '1536px', margin: '0 auto' }}>
          
          <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
            
            {/* Legal Info */}
            <div>
              <p style={{ fontSize: '14px', color: 'white', marginBottom: '8px' }}>
                © {new Date().getFullYear()} Blackline Guardian Fund. All rights reserved.
              </p>
              <RichText
                tagName="p"
                value={legalInfo}
                onChange={(value) => setAttributes({ legalInfo: value })}
                placeholder={__('Enter legal information...', 'blacklineguardianfund-theme')}
                style={{ fontSize: '14px', color: '#B2B2B2', margin: 0 }}
              />
            </div>
            
            {/* Legal Links */}
            <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: '24px' }}>
              {legalLinks.map((link, index) => (
                link.text && (
                  <a
                    key={index}
                    href={link.url}
                    style={{ fontSize: '14px', color: 'white', textDecoration: 'none' }}
                  >
                    {link.text}
                  </a>
                )
              ))}
            </div>
            
          </div>
          
        </div>
        
      </div>
    </>
  );
}

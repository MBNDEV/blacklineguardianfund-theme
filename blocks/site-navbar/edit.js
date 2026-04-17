import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  Button,
  ColorPicker,
  SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

export default function Edit( { attributes, setAttributes } ) {
  const {
    logoUrl,
    logoId,
    logoAlt,
    menuId,
    donateLabel,
    donateUrl,
    bgColor,
  } = attributes;

  // Fetch available WordPress menus
  const menus = useSelect( ( select ) => {
    return select( coreStore ).getEntityRecords( 'taxonomy', 'nav_menu', {
      per_page: -1,
    } );
  }, [] );

  const menuOptions = menus
    ? [
        { label: __( '— Select a Menu —', 'blacklineguardianfund-theme' ), value: 0 },
        ...menus.map( ( menu ) => ( {
          label: menu.name,
          value: menu.id,
        } ) ),
      ]
    : [ { label: __( 'Loading...', 'blacklineguardianfund-theme' ), value: 0 } ];

  const blockProps = useBlockProps( {
    className: 'site-navbar',
    style: { backgroundColor: bgColor },
  } );

  const selectedMenu = menus?.find( ( menu ) => menu.id === menuId );

  return (
    <>
      { /* ── Inspector sidebar ── */ }
      <InspectorControls>

        <PanelBody title={ __( 'Logo', 'blacklineguardianfund-theme' ) } initialOpen={ true }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( media ) =>
                setAttributes( { logoUrl: media.url, logoId: media.id } )
              }
              allowedTypes={ [ 'image' ] }
              value={ logoId }
              render={ ( { open } ) => (
                <div>
                  { logoUrl && (
                    <img
                      src={ logoUrl }
                      alt={ logoAlt }
                      style={ { maxHeight: 48, marginBottom: 8 } }
                    />
                  ) }
                  <Button onClick={ open } variant="secondary">
                    { logoUrl
                      ? __( 'Replace Logo', 'blacklineguardianfund-theme' )
                      : __( 'Select Logo', 'blacklineguardianfund-theme' ) }
                  </Button>
                  { logoUrl && (
                    <Button
                      onClick={ () =>
                        setAttributes( { logoUrl: '', logoId: 0 } )
                      }
                      variant="link"
                      isDestructive
                      style={ { marginLeft: 8 } }
                    >
                      { __( 'Remove', 'blacklineguardianfund-theme' ) }
                    </Button>
                  ) }
                </div>
              ) }
            />
          </MediaUploadCheck>
          <TextControl
            label={ __( 'Logo Alt Text', 'blacklineguardianfund-theme' ) }
            value={ logoAlt }
            onChange={ ( value ) => setAttributes( { logoAlt: value } ) }
          />
        </PanelBody>

        <PanelBody title={ __( 'Navigation Menu', 'blacklineguardianfund-theme' ) } initialOpen={ true }>
          <SelectControl
            label={ __( 'Select Menu', 'blacklineguardianfund-theme' ) }
            value={ menuId }
            options={ menuOptions }
            onChange={ ( value ) => setAttributes( { menuId: parseInt( value, 10 ) } ) }
            help={ __( 'Choose a WordPress menu to display. Create menus in Appearance > Menus.', 'blacklineguardianfund-theme' ) }
          />
        </PanelBody>

        <PanelBody title={ __( 'Donate Button', 'blacklineguardianfund-theme' ) } initialOpen={ false }>
          <TextControl
            label={ __( 'Button Label', 'blacklineguardianfund-theme' ) }
            value={ donateLabel }
            onChange={ ( value ) => setAttributes( { donateLabel: value } ) }
          />
          <TextControl
            label={ __( 'Button URL', 'blacklineguardianfund-theme' ) }
            value={ donateUrl }
            onChange={ ( value ) => setAttributes( { donateUrl: value } ) }
          />
        </PanelBody>

        <PanelBody title={ __( 'Background Color', 'blacklineguardianfund-theme' ) } initialOpen={ false }>
          <ColorPicker
            color={ bgColor }
            onChange={ ( value ) => setAttributes( { bgColor: value } ) }
            enableAlpha={ false }
          />
        </PanelBody>

      </InspectorControls>

      { /* ── Editor preview ── */ }
      <header { ...blockProps }>
        <div className="site-navbar__inner">

          { /* Logo */ }
          <div className="site-navbar__logo">
            { logoUrl ? (
              <img src={ logoUrl } alt={ logoAlt } className="site-navbar__logo-img" />
            ) : (
              <span className="site-navbar__logo-placeholder">
                { __( 'Select a logo →', 'blacklineguardianfund-theme' ) }
              </span>
            ) }
          </div>

          { /* Nav links */ }
          <nav className="site-navbar__nav" aria-label={ __( 'Primary navigation', 'blacklineguardianfund-theme' ) }>
            { selectedMenu ? (
              <div className="site-navbar__nav-preview">
                <p style={ { margin: 0, fontSize: '14px', color: '#666' } }>
                  { __( 'Menu:', 'blacklineguardianfund-theme' ) } <strong>{ selectedMenu.name }</strong>
                </p>
                <p style={ { margin: '4px 0 0', fontSize: '12px', color: '#999' } }>
                  { __( '(Menu items will appear on the frontend)', 'blacklineguardianfund-theme' ) }
                </p>
              </div>
            ) : (
              <p style={ { margin: 0, fontSize: '14px', color: '#999' } }>
                { __( 'No menu selected', 'blacklineguardianfund-theme' ) }
              </p>
            ) }
          </nav>

          { /* Donate button */ }
          <a href={ donateUrl } className="site-navbar__donate" onClick={ ( e ) => e.preventDefault() }>
            { donateLabel }
            <span className="site-navbar__donate-arrow" aria-hidden="true">
              <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.5 8.5L7.5 3.5M7.5 3.5H3M7.5 3.5V8" stroke="white" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round"/>
              </svg>
            </span>
          </a>

        </div>
      </header>
    </>
  );
}

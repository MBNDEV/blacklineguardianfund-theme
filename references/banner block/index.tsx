import { registerBlockType } from '@wordpress/blocks';
import {
  useBlockProps,
  RichText,
  MediaUpload,
  MediaUploadCheck,
  InspectorControls,
  PanelColorSettings,
} from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  Button,
  __experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

interface BannerAttributes {
  heading: string;
  subtitle: string;
  bulletItems: string[];
  formTitle: string;
  ctaLabel: string;
  ctaUrl: string;
  phoneNumber: string;
  logoUrl: string;
  logoId: number;
  heroImageUrl: string;
  heroImageId: number;
  overlayImageUrl: string;
  overlayImageId: number;
  phoneIconUrl: string;
  phoneIconId: number;
  gravityFormId: number;
  gradientStartColor: string;
  gradientEndColor: string;
  textColor: string;
  ctaBgColor: string;
  ctaTextColor: string;
  align: string;
}

type MediaSelection = {
  id: number;
  url: string;
};

const PLUGIN_URL = ( window as unknown as Record< string, unknown > ).mbnLandersUrl as string || '';

const Edit = ( {
  attributes,
  setAttributes,
}: {
  attributes: BannerAttributes;
  setAttributes: ( attrs: Partial< BannerAttributes > ) => void;
} ) => {
  const blockProps = useBlockProps( {
    className:
      'mbn-banner-1 flex px-5 py-5 flex-col items-center relative border-b-14 border-gray',
    style: {
      background: `linear-gradient(129deg, ${ attributes.gradientStartColor } 14.23%, ${ attributes.gradientEndColor } 88.76%)`,
    },
  } );

  const logoSrc = attributes.logoUrl || `${ PLUGIN_URL }assets/img/defaults/logo.svg`;
  const heroSrc = attributes.heroImageUrl || `${ PLUGIN_URL }assets/img/defaults/hva-5star.webp`;
  const overlaySrc = attributes.overlayImageUrl || `${ PLUGIN_URL }assets/img/defaults/overlay-img.webp`;

  const textStyle: React.CSSProperties = { color: attributes.textColor };
  const phoneIconSrc = attributes.phoneIconUrl || `${ PLUGIN_URL }assets/img/defaults/phone-icon.svg`;
  const phoneIconStyle: React.CSSProperties = {
    ...textStyle,
    '--phone-icon-url': `url(${ phoneIconSrc })`,
  } as React.CSSProperties;
  const ctaStyle: React.CSSProperties = {
    backgroundColor: attributes.ctaBgColor || undefined,
    color: attributes.ctaTextColor,
  };

  const updateBullet = ( index: number, value: string ) => {
    const updated = [ ...attributes.bulletItems ];
    updated[ index ] = value;
    setAttributes( { bulletItems: updated } );
  };

  const addBullet = () => {
    setAttributes( {
      bulletItems: [ ...attributes.bulletItems, 'New item' ],
    } );
  };

  const removeBullet = ( index: number ) => {
    const updated = attributes.bulletItems.filter(
      ( _, i ) => i !== index
    );
    setAttributes( { bulletItems: updated } );
  };

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={ __( 'Banner Settings', 'mbn-landers' ) }
          initialOpen={ true }
        >
          <TextControl
            label={ __( 'Phone Number', 'mbn-landers' ) }
            value={ attributes.phoneNumber }
            onChange={ ( phoneNumber ) =>
              setAttributes( { phoneNumber } )
            }
          />
          <TextControl
            label={ __( 'CTA Label', 'mbn-landers' ) }
            value={ attributes.ctaLabel }
            onChange={ ( ctaLabel ) =>
              setAttributes( { ctaLabel } )
            }
          />
          <TextControl
            label={ __( 'CTA URL', 'mbn-landers' ) }
            value={ attributes.ctaUrl }
            onChange={ ( ctaUrl ) =>
              setAttributes( { ctaUrl } )
            }
            help={ __( 'Use #banner-form to scroll to the form, or enter a full URL.', 'mbn-landers' ) }
          />
          <NumberControl
            label={ __( 'Gravity Form ID', 'mbn-landers' ) }
            value={ attributes.gravityFormId }
            onChange={ ( val ) =>
              setAttributes( {
                gravityFormId: val ? parseInt( String( val ), 10 ) : 0,
              } )
            }
            min={ 0 }
          />
        </PanelBody>
        <PanelBody
          title={ __( 'Images', 'mbn-landers' ) }
          initialOpen={ false }
        >
          <MediaUploadCheck>
            <p className="components-base-control__label">
              { __( 'Logo', 'mbn-landers' ) }
            </p>
            <MediaUpload
              onSelect={ ( media: MediaSelection ) =>
                setAttributes( {
                  logoId: media.id,
                  logoUrl: media.url,
                } )
              }
              allowedTypes={ [ 'image' ] }
              value={ attributes.logoId }
              render={ ( {
                open,
              }: {
                open: () => void;
              } ) => (
                <Button variant="secondary" onClick={ open }>
                  { attributes.logoId
                    ? __( 'Replace Logo', 'mbn-landers' )
                    : __( 'Upload Logo', 'mbn-landers' ) }
                </Button>
              ) }
            />
          </MediaUploadCheck>
          <MediaUploadCheck>
            <p
              className="components-base-control__label"
              style={ { marginTop: '16px' } }
            >
              { __( 'Hero Image', 'mbn-landers' ) }
            </p>
            <MediaUpload
              onSelect={ ( media: MediaSelection ) =>
                setAttributes( {
                  heroImageId: media.id,
                  heroImageUrl: media.url,
                } )
              }
              allowedTypes={ [ 'image' ] }
              value={ attributes.heroImageId }
              render={ ( {
                open,
              }: {
                open: () => void;
              } ) => (
                <Button variant="secondary" onClick={ open }>
                  { attributes.heroImageId
                    ? __(
                        'Replace Hero Image',
                        'mbn-landers'
                      )
                    : __(
                        'Upload Hero Image',
                        'mbn-landers'
                      ) }
                </Button>
              ) }
            />
          </MediaUploadCheck>
          <MediaUploadCheck>
            <p
              className="components-base-control__label"
              style={ { marginTop: '16px' } }
            >
              { __( 'Overlay Image', 'mbn-landers' ) }
            </p>
            <MediaUpload
              onSelect={ ( media: MediaSelection ) =>
                setAttributes( {
                  overlayImageId: media.id,
                  overlayImageUrl: media.url,
                } )
              }
              allowedTypes={ [ 'image' ] }
              value={ attributes.overlayImageId }
              render={ ( {
                open,
              }: {
                open: () => void;
              } ) => (
                <Button variant="secondary" onClick={ open }>
                  { attributes.overlayImageId
                    ? __(
                        'Replace Overlay',
                        'mbn-landers'
                      )
                    : __(
                        'Upload Overlay',
                        'mbn-landers'
                      ) }
                </Button>
              ) }
            />
          </MediaUploadCheck>
          <MediaUploadCheck>
            <p
              className="components-base-control__label"
              style={ { marginTop: '16px' } }
            >
              { __( 'Phone Icon', 'mbn-landers' ) }
            </p>
            <MediaUpload
              onSelect={ ( media: MediaSelection ) =>
                setAttributes( {
                  phoneIconId: media.id,
                  phoneIconUrl: media.url,
                } )
              }
              allowedTypes={ [ 'image' ] }
              value={ attributes.phoneIconId }
              render={ ( {
                open,
              }: {
                open: () => void;
              } ) => (
                <Button variant="secondary" onClick={ open }>
                  { attributes.phoneIconId
                    ? __( 'Replace Phone Icon', 'mbn-landers' )
                    : __( 'Upload Phone Icon', 'mbn-landers' ) }
                </Button>
              ) }
            />
          </MediaUploadCheck>
        </PanelBody>
        <PanelBody
          title={ __( 'Bullet Points', 'mbn-landers' ) }
          initialOpen={ false }
        >
          { attributes.bulletItems.map( ( item, i ) => (
            <div
              key={ i }
              style={ {
                display: 'flex',
                gap: '4px',
                marginBottom: '8px',
              } }
            >
              <TextControl
                value={ item }
                onChange={ ( val ) =>
                  updateBullet( i, val )
                }
              />
              <Button
                isDestructive
                variant="tertiary"
                onClick={ () => removeBullet( i ) }
                size="small"
              >
                ✕
              </Button>
            </div>
          ) ) }
          <Button variant="secondary" onClick={ addBullet }>
            { __( 'Add Bullet', 'mbn-landers' ) }
          </Button>
        </PanelBody>
        <PanelColorSettings
          title={ __( 'Colors', 'mbn-landers' ) }
          initialOpen={ false }
          colorSettings={ [
            {
              value: attributes.gradientStartColor,
              onChange: ( val: string | undefined ) =>
                setAttributes( { gradientStartColor: val ?? '#1DB7F9' } ),
              label: __( 'Gradient Start', 'mbn-landers' ),
            },
            {
              value: attributes.gradientEndColor,
              onChange: ( val: string | undefined ) =>
                setAttributes( { gradientEndColor: val ?? '#01539D' } ),
              label: __( 'Gradient End', 'mbn-landers' ),
            },
            {
              value: attributes.textColor,
              onChange: ( val: string | undefined ) =>
                setAttributes( { textColor: val ?? '#ffffff' } ),
              label: __( 'Text Color', 'mbn-landers' ),
            },
            {
              value: attributes.ctaBgColor || undefined,
              onChange: ( val: string | undefined ) =>
                setAttributes( { ctaBgColor: val ?? '' } ),
              label: __( 'CTA Button Background', 'mbn-landers' ),
            },
            {
              value: attributes.ctaTextColor,
              onChange: ( val: string | undefined ) =>
                setAttributes( { ctaTextColor: val ?? '#ffffff' } ),
              label: __( 'CTA Button Text', 'mbn-landers' ),
            },
          ] }
        />
      </InspectorControls>

      <section { ...blockProps }>
        <div className="flex flex-col max-w-400 w-full relative z-20">
          { /* Header row */ }
          <div className="flex flex-row justify-between items-center mb-6">
            <img
              src={ logoSrc }
              alt="logo"
              className="max-h-16"
            />
            <div className="flex flex-row items-center">
              <span className="mr-5 text-white text-[26px] phone_number_icon hidden lg:block" style={ phoneIconStyle }>
                { attributes.phoneNumber }
              </span>
              <span className="bg-primary text-white rounded-full uppercase px-6.75 py-4.5 font-bold lg:text-lg lg:tracking-wider" style={ ctaStyle }>
                { attributes.ctaLabel }
              </span>
            </div>
          </div>

          { /* Heading area */ }
          <div className="flex flex-col items-center mb-12">
            <RichText
              tagName="h1"
              className="block text-white text-[32px] lg:text-[64px] text-center mb-2 font-poppins leading-8 lg:leading-15 font-semibold"
              style={ textStyle }
              value={ attributes.heading }
              onChange={ ( heading: string ) =>
                setAttributes( { heading } )
              }
              placeholder={ __(
                'Your Headline Here',
                'mbn-landers'
              ) }
            />
            <RichText
              tagName="h2"
              className="block text-white text-base md:text-xl lg:text-[24px] mb-4 font-opensans font-thin"
              style={ textStyle }
              value={ attributes.subtitle }
              onChange={ ( subtitle: string ) =>
                setAttributes( { subtitle } )
              }
              placeholder={ __(
                'Your subtitle here',
                'mbn-landers'
              ) }
            />
            <ul className="flex flex-col lg:flex-row items-center justify-evenly text-base md:text-xl lg:text-[24px] text-white lg:gap-8 font-opensans" style={ textStyle }>
              { attributes.bulletItems.map( ( item, i ) => (
                <li
                  key={ i }
                  dangerouslySetInnerHTML={ {
                    __html: item,
                  } }
                />
              ) ) }
            </ul>
          </div>

          { /* Hero image + form placeholder */ }
          <div className="flex flex-col lg:flex-row -mb-25 w-full lg:justify-between">
            <div className="flex flex-col lg:w-1/2 mb-5 lg:mb-0 pr-0 lg:pr-5">
              <img
                src={ heroSrc }
                alt="hero"
                className="lg:max-w-141.25 lg:mt-25 lg:ml-10 block mx-auto lg:mx-0"
              />
            </div>
            <div className="flex flex-col lg:w-1/2 lg:max-w-177.5">
              <div className="flex w-full flex-col rounded-xl p-6 lg:p-10 bg-white shadow-[0px_4px_80px_0px_rgba(0,0,0,0.20)]">
                <RichText
                  tagName="h3"
                  className="text-center block font-semibold text-xl md:text-2xl lg:text-[26px] mb-8 text-black"
                  value={ attributes.formTitle }
                  onChange={ ( formTitle: string ) =>
                    setAttributes( { formTitle } )
                  }
                  placeholder={ __(
                    'Form heading',
                    'mbn-landers'
                  ) }
                />
                <div className="text-gray-500 text-center text-sm border border-dashed border-gray-300 rounded p-4">
                  { attributes.gravityFormId > 0
                    ? `[Gravity Form ID: ${ attributes.gravityFormId }]`
                    : __(
                        'Set Gravity Form ID in the sidebar →',
                        'mbn-landers'
                      ) }
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="w-full h-full absolute top-0 left-0 overflow-hidden">
          <img
          src={ overlaySrc }
          className="w-full h-full absolute top-0 left-0 opacity-50 z-10 object-cover"
          alt=""
        />
        </div>
      </section>
    </>
  );
};

registerBlockType( metadata.name, {
  edit: Edit,
  save: () => null,
} );

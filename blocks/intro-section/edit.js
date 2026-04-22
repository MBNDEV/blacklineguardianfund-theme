import { useBlockProps, InspectorControls, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
  const {
    bgImageUrl,
    bgImageId,
    heading,
    paragraph1,
    paragraph2,
    photoUrl,
    photoId,
    cardsHeading,
    cards,
    shieldIconUrl,
    shieldIconId,
    tagline,
  } = attributes;

  const blockProps = useBlockProps( {
    className: 'w-full bg-cream py-16 lg:py-24',
  } );

  function updateCard( index, field, value ) {
    const updated = cards.map( ( card, i ) =>
      i === index ? { ...card, [ field ]: value } : card
    );
    setAttributes( { cards: updated } );
  }

  function addCard() {
    setAttributes( { cards: [ ...cards, { iconUrl: '', label: '' } ] } );
  }

  function removeCard( index ) {
    setAttributes( { cards: cards.filter( ( _, i ) => i !== index ) } );
  }

  return (
    <>
      <InspectorControls>

        {/* Background Image */}
        <PanelBody title={ __( 'Background Image', 'mbn-theme' ) } initialOpen={ true }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( media ) => setAttributes( { bgImageUrl: media.url, bgImageId: media.id } ) }
              allowedTypes={ [ 'image' ] }
              value={ bgImageId }
              render={ ( { open } ) => (
                <>
                  { bgImageUrl && (
                    <img src={ bgImageUrl } alt="" className="w-full rounded mb-2" style={ { maxHeight: '80px', objectFit: 'cover' } } />
                  ) }
                  <Button onClick={ open } variant="primary">
                    { bgImageUrl
                      ? __( 'Replace Background', 'mbn-theme' )
                      : __( 'Select Background Image', 'mbn-theme' ) }
                  </Button>
                  { bgImageUrl && (
                    <Button
                      onClick={ () => setAttributes( { bgImageUrl: '', bgImageId: undefined } ) }
                      variant="secondary"
                      isDestructive
                      style={ { marginTop: '8px' } }
                    >
                      { __( 'Remove Background', 'mbn-theme' ) }
                    </Button>
                  ) }
                </>
              ) }
            />
          </MediaUploadCheck>
        </PanelBody>

        {/* Text Content */}
        <PanelBody title={ __( 'Text Content', 'mbn-theme' ) } initialOpen={ false }>
          <TextControl
            label={ __( 'Heading', 'mbn-theme' ) }
            value={ heading }
            onChange={ ( value ) => setAttributes( { heading: value } ) }
          />
          <TextareaControl
            label={ __( 'Paragraph 1', 'mbn-theme' ) }
            value={ paragraph1 }
            onChange={ ( value ) => setAttributes( { paragraph1: value } ) }
          />
          <TextareaControl
            label={ __( 'Paragraph 2', 'mbn-theme' ) }
            value={ paragraph2 }
            onChange={ ( value ) => setAttributes( { paragraph2: value } ) }
          />
        </PanelBody>

        {/* Right Photo */}
        <PanelBody title={ __( 'Section Photo', 'mbn-theme' ) } initialOpen={ false }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( media ) => setAttributes( { photoUrl: media.url, photoId: media.id } ) }
              allowedTypes={ [ 'image' ] }
              value={ photoId }
              render={ ( { open } ) => (
                <>
                  { photoUrl && (
                    <img src={ photoUrl } alt="" className="w-full rounded mb-2" style={ { maxHeight: '120px', objectFit: 'cover' } } />
                  ) }
                  <Button onClick={ open } variant="primary">
                    { photoUrl
                      ? __( 'Replace Photo', 'mbn-theme' )
                      : __( 'Select Photo', 'mbn-theme' ) }
                  </Button>
                  { photoUrl && (
                    <Button
                      onClick={ () => setAttributes( { photoUrl: '', photoId: undefined } ) }
                      variant="secondary"
                      isDestructive
                      style={ { marginTop: '8px' } }
                    >
                      { __( 'Remove Photo', 'mbn-theme' ) }
                    </Button>
                  ) }
                </>
              ) }
            />
          </MediaUploadCheck>
        </PanelBody>

        {/* Category Cards */}
        <PanelBody title={ __( 'Category Cards', 'mbn-theme' ) } initialOpen={ false }>
          <TextControl
            label={ __( 'Cards Heading', 'mbn-theme' ) }
            value={ cardsHeading }
            onChange={ ( value ) => setAttributes( { cardsHeading: value } ) }
          />
          { cards.map( ( card, index ) => (
            <div key={ index } style={ { border: '1px solid #ddd', borderRadius: '4px', padding: '12px', marginBottom: '12px' } }>
              <strong>{ __( 'Card', 'mbn-theme' ) } { index + 1 }</strong>
              <TextControl
                label={ __( 'Label', 'mbn-theme' ) }
                value={ card.label }
                onChange={ ( value ) => updateCard( index, 'label', value ) }
              />
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={ ( media ) => updateCard( index, 'iconUrl', media.url ) }
                  allowedTypes={ [ 'image' ] }
                  value={ card.iconUrl }
                  render={ ( { open } ) => (
                    <>
                      { card.iconUrl && (
                        <img src={ card.iconUrl } alt="" style={ { width: '40px', height: '40px', objectFit: 'contain', marginBottom: '6px' } } />
                      ) }
                      <Button onClick={ open } variant="secondary" size="small">
                        { card.iconUrl
                          ? __( 'Replace Icon', 'mbn-theme' )
                          : __( 'Select Icon', 'mbn-theme' ) }
                      </Button>
                    </>
                  ) }
                />
              </MediaUploadCheck>
              { cards.length > 1 && (
                <Button
                  onClick={ () => removeCard( index ) }
                  variant="secondary"
                  isDestructive
                  size="small"
                  style={ { marginTop: '8px' } }
                >
                  { __( 'Remove Card', 'mbn-theme' ) }
                </Button>
              ) }
            </div>
          ) ) }
          <Button onClick={ addCard } variant="secondary">
            { __( '+ Add Card', 'mbn-theme' ) }
          </Button>
        </PanelBody>

        {/* Tagline */}
        <PanelBody title={ __( 'Tagline', 'mbn-theme' ) } initialOpen={ false }>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={ ( media ) => setAttributes( { shieldIconUrl: media.url, shieldIconId: media.id } ) }
              allowedTypes={ [ 'image' ] }
              value={ shieldIconId }
              render={ ( { open } ) => (
                <>
                  { shieldIconUrl && (
                    <img src={ shieldIconUrl } alt="" style={ { width: '32px', height: '32px', objectFit: 'contain', marginBottom: '6px' } } />
                  ) }
                  <Button onClick={ open } variant="secondary">
                    { shieldIconUrl
                      ? __( 'Replace Shield Icon', 'mbn-theme' )
                      : __( 'Select Shield Icon', 'mbn-theme' ) }
                  </Button>
                </>
              ) }
            />
          </MediaUploadCheck>
          <TextControl
            label={ __( 'Tagline Text', 'mbn-theme' ) }
            value={ tagline }
            onChange={ ( value ) => setAttributes( { tagline: value } ) }
            style={ { marginTop: '12px' } }
          />
        </PanelBody>

      </InspectorControls>

      {/* Editor Preview */}
      <section { ...blockProps } style={ bgImageUrl ? { backgroundImage: `url(${bgImageUrl})`, backgroundSize: 'cover', backgroundPosition: 'center' } : {} }>
        <div className="max-w-screen-2xl mx-auto px-6 lg:px-16">

          {/* Top: Text + Photo */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start mb-16 lg:mb-20">
            <div>
              <RichText
                tagName="h2"
                value={ heading }
                onChange={ ( value ) => setAttributes( { heading: value } ) }
                placeholder={ __( 'Why we exist...', 'mbn-theme' ) }
                className="font-sofia font-bold text-5xl lg:text-6xl text-dark-text mb-6"
              />
              <RichText
                tagName="p"
                value={ paragraph1 }
                onChange={ ( value ) => setAttributes( { paragraph1: value } ) }
                placeholder={ __( 'First paragraph...', 'mbn-theme' ) }
                className="font-inter font-light text-base text-dark-text leading-relaxed mb-5"
              />
              <RichText
                tagName="p"
                value={ paragraph2 }
                onChange={ ( value ) => setAttributes( { paragraph2: value } ) }
                placeholder={ __( 'Second paragraph...', 'mbn-theme' ) }
                className="font-inter font-light text-base text-dark-text leading-relaxed"
              />
            </div>
            <div className="rounded-2xl overflow-hidden w-full max-w-[608px] aspect-[608/446] bg-gray-100 flex items-center justify-center">
              { photoUrl ? (
                <img src={ photoUrl } alt="" className="w-full h-full object-cover" />
              ) : (
                <span className="text-gray-400 text-sm">{ __( 'Select photo in sidebar', 'mbn-theme' ) }</span>
              ) }
            </div>
          </div>

          {/* Cards */}
          <div className="mb-12 lg:mb-16">
            <RichText
              tagName="h3"
              value={ cardsHeading }
              onChange={ ( value ) => setAttributes( { cardsHeading: value } ) }
              className="font-inter text-lg lg:text-2xl font-semibold text-dark-text mb-6"
            />
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
              { cards.map( ( card, index ) => (
                <div key={ index } className="category-card flex flex-col items-center justify-center gap-4 py-8 px-4">
                  { card.iconUrl ? (
                    <img src={ card.iconUrl } alt="" className="w-16 h-16 object-contain" />
                  ) : (
                    <div className="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-xs">Icon</div>
                  ) }
                  <span className="font-sofia font-bold text-2xl uppercase text-card-label text-center leading-tight">
                    { card.label || __( 'Card Label', 'mbn-theme' ) }
                  </span>
                </div>
              ) ) }
            </div>
          </div>

          {/* Tagline */}
          <div className="flex items-center gap-4">
            <div className="shrink-0 w-8 h-8">
              { shieldIconUrl ? (
                <img src={ shieldIconUrl } alt="" className="w-full h-full object-contain" />
              ) : (
                <svg viewBox="0 0 32 36" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full h-full">
                  <path d="M16 1L1 6.5V18C1 26.8 7.8 34.4 16 36C24.2 34.4 31 26.8 31 18V6.5L16 1Z" fill="none" stroke="#25272B" strokeWidth="2" strokeLinejoin="round"/>
                  <path d="M10 18l4.5 4.5L22 13" stroke="#25272B" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                </svg>
              ) }
            </div>
            <RichText
              tagName="p"
              value={ tagline }
              onChange={ ( value ) => setAttributes( { tagline: value } ) }
              placeholder={ __( 'Tagline text...', 'mbn-theme' ) }
              className="font-sofia font-bold text-lg lg:text-3xl uppercase text-dark-text shrink-0"
            />
            <div className="flex-grow h-px bg-gold/40"></div>
          </div>

        </div>
      </section>
    </>
  );
}

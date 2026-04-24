import { useBlockProps, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, Button, SelectControl, TextControl, TextareaControl, IconButton, Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { DndContext, closestCenter, KeyboardSensor, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { arrayMove, SortableContext, sortableKeyboardCoordinates, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

// Sortable Card Item Component
function SortableCardItem({ card, index, updateCard, removeCard, duplicateCard }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: `card-${index}` });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
    marginBottom: '20px',
    padding: '15px',
    border: '1px solid #ddd',
    borderRadius: '4px',
    backgroundColor: '#fff',
  };

  return (
    <div ref={setNodeRef} style={style}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
          <div {...attributes} {...listeners} style={{ cursor: 'grab', padding: '5px' }}>
            <Icon icon="menu" />
          </div>
          <strong>{__('Card', 'mbn-theme')} {index + 1}</strong>
        </div>
        <div style={{ display: 'flex', gap: '8px' }}>
          <IconButton
            icon="admin-page"
            label={__('Duplicate', 'mbn-theme')}
            onClick={() => duplicateCard(index)}
          />
          <IconButton
            icon="trash"
            label={__('Remove', 'mbn-theme')}
            onClick={() => removeCard(index)}
          />
        </div>
      </div>

      <SelectControl
        label={__('Layout', 'mbn-theme')}
        value={card.layout}
        options={[
          { label: __('35%', 'mbn-theme'), value: '35-65' },
          { label: __('65%', 'mbn-theme'), value: '65-35' },
          { label: __('50%', 'mbn-theme'), value: '50-50' }
        ]}
        onChange={(value) => updateCard(index, { layout: value })}
      />

      <TextControl
        label={__('Heading', 'mbn-theme')}
        value={card.heading}
        onChange={(value) => updateCard(index, { heading: value })}
      />

      <TextareaControl
        label={__('Text', 'mbn-theme')}
        value={card.text}
        onChange={(value) => updateCard(index, { text: value })}
        rows={3}
      />

      <MediaUpload
        onSelect={(media) => {
          updateCard(index, { imageUrl: media.url, imageId: media.id });
        }}
        allowedTypes={['image']}
        value={card.imageId}
        render={({ open }) => (
          <div>
            <Button onClick={open} variant="secondary" style={{ marginTop: '10px' }}>
              {card.imageUrl ? __('Replace Image', 'mbn-theme') : __('Select Image', 'mbn-theme')}
            </Button>
            {card.imageUrl && (
              <img src={card.imageUrl} alt="" style={{ marginTop: '10px', maxWidth: '100%', height: 'auto', borderRadius: '4px' }} />
            )}
          </div>
        )}
      />
    </div>
  );
}

export default function Edit({ attributes, setAttributes }) {
  const { backgroundImageUrl, backgroundImageId, heading, subtext, cards } = attributes;

  const blockProps = useBlockProps({
    className: 'relative w-full py-20 md:py-32 lg:py-24 overflow-hidden',
    style: backgroundImageUrl ? {
      backgroundImage: `url(${backgroundImageUrl})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    } : {
      backgroundColor: '#f3f4f6'
    }
  });

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  const updateCard = (index, updates) => {
    const updatedCards = [...cards];
    updatedCards[index] = { ...updatedCards[index], ...updates };
    setAttributes({ cards: updatedCards });
  };

  const addCard = () => {
    setAttributes({
      cards: [...cards, { layout: '50-50', heading: '', text: '', imageUrl: '', imageId: 0 }]
    });
  };

  const removeCard = (index) => {
    const updatedCards = cards.filter((_, i) => i !== index);
    setAttributes({ cards: updatedCards });
  };

  const duplicateCard = (index) => {
    const cardToDuplicate = { ...cards[index] };
    const updatedCards = [
      ...cards.slice(0, index + 1),
      cardToDuplicate,
      ...cards.slice(index + 1)
    ];
    setAttributes({ cards: updatedCards });
  };

  const handleDragEnd = (event) => {
    const { active, over } = event;

    if (active.id !== over.id) {
      const oldIndex = parseInt(active.id.split('-')[1]);
      const newIndex = parseInt(over.id.split('-')[1]);
      
      setAttributes({
        cards: arrayMove(cards, oldIndex, newIndex),
      });
    }
  };

  const getLayoutClasses = (layout) => {
    switch(layout) {
      case '35-65':
        return 'col-span-12 md:col-span-6 lg:col-span-5';
      case '65-35':
        return 'col-span-12 md:col-span-6 lg:col-span-7';
      case '50-50':
        return 'col-span-12 md:col-span-6';
      default:
        return 'col-span-12 md:col-span-6';
    }
  };

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Background Settings', 'mbn-theme')}>
          <MediaUpload
            onSelect={(media) => setAttributes({
              backgroundImageUrl: media.url,
              backgroundImageId: media.id
            })}
            allowedTypes={['image']}
            value={backgroundImageId}
            render={({ open }) => (
              <Button onClick={open} variant="primary">
                {backgroundImageUrl ? __('Replace Background', 'mbn-theme') : __('Select Background', 'mbn-theme')}
              </Button>
            )}
          />
          {backgroundImageUrl && (
            <img src={backgroundImageUrl} alt="" className="mt-3 max-w-full h-auto rounded" />
          )}
        </PanelBody>

        <PanelBody title={__('Content Settings', 'mbn-theme')} initialOpen={false}>
          <TextControl
            label={__('Heading', 'mbn-theme')}
            value={heading}
            onChange={(value) => setAttributes({ heading: value })}
          />
          <TextareaControl
            label={__('Subtext', 'mbn-theme')}
            value={subtext}
            onChange={(value) => setAttributes({ subtext: value })}
            rows={4}
          />
        </PanelBody>

        <PanelBody title={__('Cards', 'mbn-theme')} initialOpen={true}>
          <p style={{ marginBottom: '15px', fontSize: '13px', color: '#666' }}>
            {__('Drag and drop to reorder cards', 'mbn-theme')}
          </p>
          
          <DndContext
            sensors={sensors}
            collisionDetection={closestCenter}
            onDragEnd={handleDragEnd}
          >
            <SortableContext
              items={cards.map((_, index) => `card-${index}`)}
              strategy={verticalListSortingStrategy}
            >
              {cards.map((card, index) => (
                <SortableCardItem
                  key={`card-${index}`}
                  card={card}
                  index={index}
                  updateCard={updateCard}
                  removeCard={removeCard}
                  duplicateCard={duplicateCard}
                />
              ))}
            </SortableContext>
          </DndContext>

          <Button variant="primary" onClick={addCard} style={{ marginTop: '15px' }}>
            {__('+ Add Card', 'mbn-theme')}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div className="container mx-auto px-6 md:px-12 lg:px-16 max-w-8xl">
          
          {/* Section Header */}
          <div className="text-center mb-14 md:mb-10">
            <RichText
              tagName="h2"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Enter heading...', 'mbn-theme')}
              className="font-sofia text-[40px] lg:text-[52px] font-bold leading-[1.1] tracking-[-0.04em] text-black mb-5"
            />
            <RichText
              tagName="p"
              value={subtext}
              onChange={(value) => setAttributes({ subtext: value })}
              placeholder={__('Enter subtext...', 'mbn-theme')}
              className="text-base lg:text-lg font-light leading-relaxed text-black max-w-3xl mx-auto"
            />
          </div>

          {/* Cards Grid */}
          <div className="grid grid-cols-1 md:grid-cols-12 gap-4 lg:gap-5">
            {cards.map((card, index) => (
              <div
                key={index}
                className={`group relative overflow-hidden rounded-lg aspect-[4/3] shadow-md max-h-auto lg:max-h-[400px] w-full h-full ${getLayoutClasses(card.layout)}`}
              >
                {card.imageUrl ? (
                  <img
                    src={card.imageUrl}
                    alt={card.heading}
                    className="absolute inset-0 w-full h-full object-cover"
                  />
                ) : (
                  <div className="absolute inset-0 bg-gray-300 flex items-center justify-center">
                    <span className="text-gray-600">{__('No image', 'mbn-theme')}</span>
                  </div>
                )}
                
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                
                <div className="absolute bottom-0 left-0 right-0 p-5 lg:p-6">
                  <span className="block text-3xl font-bold leading-tight tracking-tight text-white">
                    {card.heading || __('Card heading...', 'mbn-theme')}
                  </span>
                  <p className="text-white text-lg font-light leading-normal mt-2">
                    {card.text || __('Card text...', 'mbn-theme')}
                  </p>
                </div>
              </div>
            ))}
          </div>

        </div>
      </div>
    </Fragment>
  );
}

import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, IconButton, Icon, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { DndContext, closestCenter, KeyboardSensor, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { arrayMove, SortableContext, sortableKeyboardCoordinates, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import AnimationControls from '../shared/AnimationControls';

// Sortable List Item Component (nested)
function SortableListItem({ listItem, itemIndex, cardIndex, updateListItem, removeListItem, duplicateListItem }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: listItem.id });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
    marginBottom: '8px',
    padding: '8px',
    border: '1px solid #e0e0e0',
    borderRadius: '4px',
    backgroundColor: '#fafafa',
  };

  return (
    <div ref={setNodeRef} style={style}>
      {/* Controls Row */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '8px' }}>
        <div {...attributes} {...listeners} style={{ cursor: 'grab', padding: '2px' }}>
          <Icon icon="menu" style={{ fontSize: '14px' }} />
        </div>
        <div style={{ display: 'flex', gap: '8px' }}>
          <IconButton
            icon="admin-page"
            label={__('Duplicate', 'mbn-theme')}
            onClick={() => duplicateListItem(cardIndex, itemIndex)}
            style={{ minWidth: 'auto' }}
          />
          <IconButton
            icon="trash"
            label={__('Remove', 'mbn-theme')}
            onClick={() => removeListItem(cardIndex, itemIndex)}
            style={{ minWidth: 'auto' }}
          />
        </div>
      </div>
      {/* Text Input Row */}
      <TextControl
        value={listItem.text}
        onChange={(value) => updateListItem(cardIndex, itemIndex, value)}
        placeholder={__('List item...', 'mbn-theme')}
        style={{ width: '100%', marginBottom: 0 }}
      />
    </div>
  );
}

// Sortable Card Item Component
function SortableCardItem({ card, index, updateCard, removeCard, duplicateCard, updateListItem, addListItem, removeListItem, duplicateListItem, handleListDragEnd }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: card.id });

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

      <MediaUpload
        onSelect={(media) => updateCard(index, { iconUrl: media.url, iconId: media.id })}
        allowedTypes={['image']}
        value={card.iconId}
        render={({ open }) => (
          <div style={{ marginBottom: '10px' }}>
            <Button onClick={open} variant="secondary">
              {card.iconUrl ? __('Replace Icon', 'mbn-theme') : __('Select Icon', 'mbn-theme')}
            </Button>
            {card.iconUrl && (
              <img src={card.iconUrl} alt="" style={{ marginTop: '10px', maxWidth: '60px', height: 'auto' }} />
            )}
          </div>
        )}
      />

      <div>
        <label style={{ display: 'block', marginBottom: '5px', fontWeight: 'bold' }}>
          {__('Heading', 'mbn-theme')}
        </label>
        <RichText
          tagName="div"
          value={card.heading}
          onChange={(value) => updateCard(index, { heading: value })}
          placeholder={__('Card heading...', 'mbn-theme')}
          className="border border-gray-300 p-2 rounded"
          allowedFormats={['core/bold', 'core/italic']}
        />
      </div>

      <div>
        <label style={{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }}>
          {__('List Items', 'mbn-theme')}
        </label>
        <p style={{ fontSize: '12px', color: '#666', marginBottom: '10px' }}>
          {__('Drag to reorder list items', 'mbn-theme')}
        </p>
        
        <DndContext
          sensors={useSensors(
            useSensor(PointerSensor),
            useSensor(KeyboardSensor, {
              coordinateGetter: sortableKeyboardCoordinates,
            })
          )}
          collisionDetection={closestCenter}
          onDragEnd={(event) => handleListDragEnd(event, index)}
        >
          <SortableContext
            items={(card.listItems || []).map((item) => item.id)}
            strategy={verticalListSortingStrategy}
          >
            {(card.listItems || []).map((listItem, itemIndex) => (
              <SortableListItem
                key={listItem.id}
                listItem={listItem}
                itemIndex={itemIndex}
                cardIndex={index}
                updateListItem={updateListItem}
                removeListItem={removeListItem}
                duplicateListItem={duplicateListItem}
              />
            ))}
          </SortableContext>
        </DndContext>

        <Button 
          variant="secondary" 
          onClick={() => addListItem(index)}
          style={{ marginTop: '10px' }}
        >
          {__('+ Add List Item', 'mbn-theme')}
        </Button>
      </div>
    </div>
  );
}

export default function Edit({ attributes, setAttributes }) {
  const { 
    heading,
    description,
    imageUrl,
    imageId,
    imageAlt,
    shieldImageUrl,
    shieldImageId,
    backgroundImageUrl,
    backgroundImageId,
    cards,
    animationType,
    animationDuration,
    animationDelay,
  } = attributes;

  // Drag and drop sensors
  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  // Card management functions
  const updateCard = (index, updates) => {
    const updatedCards = [...cards];
    updatedCards[index] = { ...updatedCards[index], ...updates };
    setAttributes({ cards: updatedCards });
  };

  const addCard = () => {
    setAttributes({
      cards: [...cards, { 
        id: crypto.randomUUID(), 
        iconUrl: '', 
        iconId: 0, 
        heading: '', 
        listItems: []
      }]
    });
  };

  const removeCard = (index) => {
    const updatedCards = cards.filter((_, i) => i !== index);
    setAttributes({ cards: updatedCards });
  };

  const duplicateCard = (index) => {
    const cardToDuplicate = { 
      ...cards[index], 
      id: crypto.randomUUID(),
      listItems: cards[index].listItems.map(item => ({ ...item, id: crypto.randomUUID() }))
    };
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
      const oldIndex = cards.findIndex(card => card.id === active.id);
      const newIndex = cards.findIndex(card => card.id === over.id);
      
      setAttributes({
        cards: arrayMove(cards, oldIndex, newIndex),
      });
    }
  };

  // List item management functions
  const updateListItem = (cardIndex, itemIndex, text) => {
    const updatedCards = [...cards];
    updatedCards[cardIndex].listItems[itemIndex].text = text;
    setAttributes({ cards: updatedCards });
  };

  const addListItem = (cardIndex) => {
    const updatedCards = [...cards];
    if (!updatedCards[cardIndex].listItems) {
      updatedCards[cardIndex].listItems = [];
    }
    updatedCards[cardIndex].listItems.push({
      id: crypto.randomUUID(),
      text: ''
    });
    setAttributes({ cards: updatedCards });
  };

  const removeListItem = (cardIndex, itemIndex) => {
    const updatedCards = [...cards];
    updatedCards[cardIndex].listItems = updatedCards[cardIndex].listItems.filter((_, i) => i !== itemIndex);
    setAttributes({ cards: updatedCards });
  };

  const duplicateListItem = (cardIndex, itemIndex) => {
    const updatedCards = [...cards];
    const itemToDuplicate = { 
      ...updatedCards[cardIndex].listItems[itemIndex], 
      id: crypto.randomUUID() 
    };
    updatedCards[cardIndex].listItems = [
      ...updatedCards[cardIndex].listItems.slice(0, itemIndex + 1),
      itemToDuplicate,
      ...updatedCards[cardIndex].listItems.slice(itemIndex + 1)
    ];
    setAttributes({ cards: updatedCards });
  };

  const handleListDragEnd = (event, cardIndex) => {
    const { active, over } = event;

    if (active.id !== over.id) {
      const updatedCards = [...cards];
      const listItems = updatedCards[cardIndex].listItems;
      const oldIndex = listItems.findIndex(item => item.id === active.id);
      const newIndex = listItems.findIndex(item => item.id === over.id);
      
      updatedCards[cardIndex].listItems = arrayMove(listItems, oldIndex, newIndex);
      setAttributes({ cards: updatedCards });
    }
  };

  const blockProps = useBlockProps({
    className: 'image-text-bullets-editor w-full py-16 md:py-20 lg:py-24 overflow-hidden',
    style: backgroundImageUrl ? {
      backgroundImage: `url(${backgroundImageUrl})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      backgroundColor: '#F9F5EE',
    } : {
      backgroundColor: '#F9F5EE',
    }
  });

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Background Image', 'mbn-theme')} initialOpen={false}>
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
                        onClick={() => setAttributes({ backgroundImageUrl: '', backgroundImageId: 0 })}
                        variant="secondary"
                        isDestructive
                        className="mt-2"
                      >
                        {__('Remove Background', 'mbn-theme')}
                      </Button>
                    </>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>

        <PanelBody title={__('Main Image', 'mbn-theme')} initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                imageUrl: media.url, 
                imageId: media.id,
                imageAlt: media.alt || ''
              })}
              allowedTypes={['image']}
              value={imageId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {imageUrl 
                      ? __('Replace Image', 'mbn-theme') 
                      : __('Select Image', 'mbn-theme')
                    }
                  </Button>
                  {imageUrl && (
                    <div className="mt-4">
                      <img 
                        src={imageUrl} 
                        alt={imageAlt || ''} 
                        className="w-full h-auto object-cover rounded"
                      />
                    </div>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>

        <PanelBody title={__('Shield Overlay Image', 'mbn-theme')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                shieldImageUrl: media.url, 
                shieldImageId: media.id
              })}
              allowedTypes={['image']}
              value={shieldImageId}
              render={({ open }) => (
                <div>
                  <Button onClick={open} variant="primary">
                    {shieldImageUrl 
                      ? __('Replace Shield Image', 'mbn-theme') 
                      : __('Select Shield Image', 'mbn-theme')
                    }
                  </Button>
                  {shieldImageUrl && (
                    <>
                      <div className="mt-4">
                        <img 
                          src={shieldImageUrl} 
                          alt="" 
                          className="w-full h-32 object-contain rounded bg-gray-100"
                        />
                      </div>
                      <Button 
                        onClick={() => setAttributes({ shieldImageUrl: '', shieldImageId: 0 })}
                        variant="secondary"
                        isDestructive
                        className="mt-2"
                      >
                        {__('Remove Shield', 'mbn-theme')}
                      </Button>
                    </>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
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
              items={cards.map((card) => card.id)}
              strategy={verticalListSortingStrategy}
            >
              {cards.map((card, index) => (
                <SortableCardItem
                  key={card.id}
                  card={card}
                  index={index}
                  updateCard={updateCard}
                  removeCard={removeCard}
                  duplicateCard={duplicateCard}
                  updateListItem={updateListItem}
                  addListItem={addListItem}
                  removeListItem={removeListItem}
                  duplicateListItem={duplicateListItem}
                  handleListDragEnd={handleListDragEnd}
                />
              ))}
            </SortableContext>
          </DndContext>

          <Button variant="primary" onClick={addCard} style={{ marginTop: '15px' }}>
            {__('+ Add Card', 'mbn-theme')}
          </Button>
        </PanelBody>

        <AnimationControls
          animationType={animationType}
          animationDuration={animationDuration}
          animationDelay={animationDelay}
          setAttributes={setAttributes}
        />
      </InspectorControls>

      <div {...blockProps}>
        <div className="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">
          
          {/* Top Section: Heading + Image */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-16 lg:mb-24">
            
            {/* Left Column: Text Content */}
            <div className="lg:col-span-5 space-y-6">
              <RichText
                tagName="h2"
                value={heading}
                onChange={(value) => setAttributes({ heading: value })}
                placeholder={__('Enter heading...', 'mbn-theme')}
                className="font-sofia text-4xl md:text-5xl lg:text-[52px] font-bold leading-[1.1] tracking-[-0.02em] text-black"
                allowedFormats={['core/bold', 'core/italic']}
              />
              
              <RichText
                tagName="p"
                value={description}
                onChange={(value) => setAttributes({ description: value })}
                placeholder={__('Enter description...', 'mbn-theme')}
                className="text-base lg:text-lg font-light font-inter leading-relaxed text-dark-text max-w-lg"
                allowedFormats={[]}
              />
            </div>

            {/* Right Column: Image with Shield Overlay */}
            <div className="lg:col-span-7 relative">
              {imageUrl ? (
                <>
                  <div className="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img 
                      src={imageUrl} 
                      alt={imageAlt || ''} 
                      className="w-full h-auto object-cover"
                    />
                  </div>
                  {/* Shield Overlay */}
                  {shieldImageUrl && (
                    <div className="absolute -top-6 -right-6 lg:-top-11 lg:-right-9 w-32 h-auto lg:w-40">
                      <img 
                        src={shieldImageUrl}
                        alt="" 
                        className="w-full h-full object-contain drop-shadow-2xl"
                      />
                    </div>
                  )}
                </>
              ) : (
                <div className="relative rounded-2xl overflow-hidden shadow-2xl bg-gray-200 aspect-video flex items-center justify-center">
                  <span className="text-gray-500">{__('No image selected', 'mbn-theme')}</span>
                </div>
              )}
            </div>
          </div>

          {/* Cards Preview */}
          {cards.length > 0 && (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
              {cards.map((card, index) => (
                <div key={card.id} className="space-y-4 border-2 border-dashed border-gray-300 p-4 rounded">
                  {card.iconUrl && (
                    <div className="w-12 h-12 lg:w-14 lg:h-14">
                      <img 
                        src={card.iconUrl} 
                        alt="" 
                        className="w-full h-full object-contain"
                      />
                    </div>
                  )}
                  {card.heading && (
                    <div 
                      className="font-sofia text-xl lg:text-2xl font-bold text-black"
                      dangerouslySetInnerHTML={{ __html: card.heading }}
                    />
                  )}
                  {card.listItems && card.listItems.length > 0 && (
                    <ul className="list-disc pl-5 space-y-2 text-sm lg:text-base font-light font-inter text-dark-text">
                      {card.listItems.map((item, idx) => (
                        item.text && <li key={idx}>{item.text}</li>
                      ))}
                    </ul>
                  )}
                </div>
              ))}
            </div>
          )}

        </div>
      </div>
    </Fragment>
  );
}

import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, ToggleControl, IconButton, Icon, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { DndContext, closestCenter, KeyboardSensor, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { arrayMove, SortableContext, sortableKeyboardCoordinates, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import AnimationControls from '../shared/AnimationControls';

// Sortable Item Component for Image and Text Repeater
function SortableItem({ item, index, updateItem, removeItem, duplicateItem }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: item.id });

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
          <strong>{__('Item', 'mbn-theme')} {index + 1}</strong>
        </div>
        <div style={{ display: 'flex', gap: '8px' }}>
          <IconButton
            icon="admin-page"
            label={__('Duplicate', 'mbn-theme')}
            onClick={() => duplicateItem(index)}
          />
          <IconButton
            icon="trash"
            label={__('Remove', 'mbn-theme')}
            onClick={() => removeItem(index)}
          />
        </div>
      </div>

      <TextControl
        label={__('Text', 'mbn-theme')}
        value={item.text}
        onChange={(value) => updateItem(index, { text: value })}
      />

      <ToggleControl
        label={__('Reverse Layout (Image on Right)', 'mbn-theme')}
        checked={item.reversed}
        onChange={(value) => updateItem(index, { reversed: value })}
        help={__('Toggle to place image on the right side', 'mbn-theme')}
      />

      <MediaUpload
        onSelect={(media) => updateItem(index, { imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' })}
        allowedTypes={['image']}
        value={item.imageId}
        render={({ open }) => (
          <div>
            <Button onClick={open} variant="secondary" style={{ marginTop: '10px' }}>
              {item.imageUrl ? __('Replace Image', 'mbn-theme') : __('Select Image', 'mbn-theme')}
            </Button>
            {item.imageUrl && (
              <img src={item.imageUrl} alt={item.imageAlt || ''} style={{ marginTop: '10px', maxWidth: '100%', height: 'auto', borderRadius: '4px' }} />
            )}
          </div>
        )}
      />
    </div>
  );
}

export default function Edit({ attributes, setAttributes }) {
  const { 
    backgroundImageUrl,
    backgroundImageId,
    topHeading,
    topHeadingMaxWidth,
    topHeadingAlignment,
    middleContentLayout,
    middleImageUrl,
    middleImageId,
    middleImageAlt,
    middleItems,
    leftText,
    leftImageUrl,
    leftImageId,
    leftImageAlt,
    rightText,
    rightImageUrl,
    rightImageId,
    rightImageAlt,
    centerIconUrl,
    centerIconId,
    centerIconAlt,
    bottomText,
    bottomTextMaxWidth,
    bottomTextAlignment,
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

  // Repeater management functions
  const updateItem = (index, updates) => {
    const updatedItems = [...middleItems];
    updatedItems[index] = { ...updatedItems[index], ...updates };
    setAttributes({ middleItems: updatedItems });
  };

  const addItem = () => {
    setAttributes({
      middleItems: [...middleItems, { id: crypto.randomUUID(), text: '', imageUrl: '', imageId: 0, imageAlt: '', reversed: false }]
    });
  };

  const removeItem = (index) => {
    const updatedItems = middleItems.filter((_, i) => i !== index);
    setAttributes({ middleItems: updatedItems });
  };

  const duplicateItem = (index) => {
    const itemToDuplicate = { ...middleItems[index], id: crypto.randomUUID() };
    const updatedItems = [
      ...middleItems.slice(0, index + 1),
      itemToDuplicate,
      ...middleItems.slice(index + 1)
    ];
    setAttributes({ middleItems: updatedItems });
  };

  const handleDragEnd = (event) => {
    const { active, over } = event;

    if (active.id !== over.id) {
      const oldIndex = middleItems.findIndex(item => item.id === active.id);
      const newIndex = middleItems.findIndex(item => item.id === over.id);
      
      setAttributes({
        middleItems: arrayMove(middleItems, oldIndex, newIndex),
      });
    }
  };

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
    <Fragment>
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

        <PanelBody title={__('Top Heading Options', 'mbn-theme')} initialOpen={false}>
          <SelectControl
            label={__('Text Alignment', 'mbn-theme')}
            value={topHeadingAlignment}
            options={[
              { label: __('Left', 'mbn-theme'), value: 'left' },
              { label: __('Center', 'mbn-theme'), value: 'center' },
              { label: __('Right', 'mbn-theme'), value: 'right' },
              { label: __('Justify', 'mbn-theme'), value: 'justify' }
            ]}
            onChange={(value) => setAttributes({ topHeadingAlignment: value })}
          />
          <SelectControl
            label={__('Max Width', 'mbn-theme')}
            value={topHeadingMaxWidth}
            options={[
              { label: __('None', 'mbn-theme'), value: 'max-w-none' },
              { label: __('Extra Small', 'mbn-theme'), value: 'max-w-xs' },
              { label: __('Small', 'mbn-theme'), value: 'max-w-sm' },
              { label: __('Medium', 'mbn-theme'), value: 'max-w-md' },
              { label: __('Large', 'mbn-theme'), value: 'max-w-lg' },
              { label: __('Extra Large', 'mbn-theme'), value: 'max-w-xl' },
              { label: __('2XL', 'mbn-theme'), value: 'max-w-2xl' },
              { label: __('3XL', 'mbn-theme'), value: 'max-w-3xl' },
              { label: __('4XL (Default)', 'mbn-theme'), value: 'max-w-4xl' },
              { label: __('5XL', 'mbn-theme'), value: 'max-w-5xl' },
              { label: __('6XL', 'mbn-theme'), value: 'max-w-6xl' },
              { label: __('7XL', 'mbn-theme'), value: 'max-w-7xl' }
            ]}
            onChange={(value) => setAttributes({ topHeadingMaxWidth: value })}
            help={__('Set the maximum width for the top heading', 'mbn-theme')}
          />
        </PanelBody>

        <PanelBody title={__('Middle Content Layout', 'mbn-theme')} initialOpen={true}>
          <SelectControl
            label={__('Layout Type', 'mbn-theme')}
            value={middleContentLayout}
            options={[
              { label: __('Grid Layout', 'mbn-theme'), value: 'grid' },
              { label: __('Image Only', 'mbn-theme'), value: 'image-only' },
              { label: __('Image and Text', 'mbn-theme'), value: 'image-text' }
            ]}
            onChange={(value) => setAttributes({ middleContentLayout: value })}
            help={__('Choose how to display the middle content section', 'mbn-theme')}
          />
        </PanelBody>

        {/* Grid Layout Fields (Original) */}
        {middleContentLayout === 'grid' && (
          <>
            <PanelBody title={__('Left Image', 'mbn-theme')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ 
                leftImageUrl: media.url, 
                leftImageId: media.id,
                leftImageAlt: media.alt || ''
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
                        alt={leftImageAlt || ''}
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
                rightImageId: media.id,
                rightImageAlt: media.alt || ''
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
                        alt={rightImageAlt || ''} 
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
                centerIconId: media.id,
                centerIconAlt: media.alt || ''
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
          </>
        )}

        {/* Image Only Layout */}
        {middleContentLayout === 'image-only' && (
          <PanelBody title={__('Middle Image', 'mbn-theme')} initialOpen={true}>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) => setAttributes({ 
                  middleImageUrl: media.url, 
                  middleImageId: media.id,
                  middleImageAlt: media.alt || ''
                })}
                allowedTypes={['image']}
                value={middleImageId}
                render={({ open }) => (
                  <div>
                    <Button onClick={open} variant="primary">
                      {middleImageUrl 
                        ? __('Replace Image', 'mbn-theme') 
                        : __('Select Image', 'mbn-theme')
                      }
                    </Button>
                    {middleImageUrl && (
                      <div className="mt-4">
                        <img 
                          src={middleImageUrl} 
                          alt={middleImageAlt || ''}
                          className="w-full h-auto object-cover rounded"
                        />
                      </div>
                    )}
                  </div>
                )}
              />
            </MediaUploadCheck>
          </PanelBody>
        )}

        {/* Image and Text Repeater Layout */}
        {middleContentLayout === 'image-text' && (
          <PanelBody title={__('Image and Text Items', 'mbn-theme')} initialOpen={true}>
            <p style={{ marginBottom: '15px', fontSize: '13px', color: '#666' }}>
              {__('Drag and drop to reorder items', 'mbn-theme')}
            </p>
            
            <DndContext
              sensors={sensors}
              collisionDetection={closestCenter}
              onDragEnd={handleDragEnd}
            >
              <SortableContext
                items={middleItems.map((item) => item.id)}
                strategy={verticalListSortingStrategy}
              >
                {middleItems.map((item, index) => (
                  <SortableItem
                    key={item.id}
                    item={item}
                    index={index}
                    updateItem={updateItem}
                    removeItem={removeItem}
                    duplicateItem={duplicateItem}
                  />
                ))}
              </SortableContext>
            </DndContext>

            <Button variant="primary" onClick={addItem} style={{ marginTop: '15px' }}>
              {__('+ Add Item', 'mbn-theme')}
            </Button>
          </PanelBody>
        )}

        <PanelBody title={__('Bottom Text Options', 'mbn-theme')} initialOpen={false}>
          <SelectControl
            label={__('Text Alignment', 'mbn-theme')}
            value={bottomTextAlignment}
            options={[
              { label: __('Left', 'mbn-theme'), value: 'left' },
              { label: __('Center', 'mbn-theme'), value: 'center' },
              { label: __('Right', 'mbn-theme'), value: 'right' },
              { label: __('Justify', 'mbn-theme'), value: 'justify' }
            ]}
            onChange={(value) => setAttributes({ bottomTextAlignment: value })}
          />
          <SelectControl
            label={__('Max Width', 'mbn-theme')}
            value={bottomTextMaxWidth}
            options={[
              { label: __('None', 'mbn-theme'), value: 'max-w-none' },
              { label: __('Extra Small', 'mbn-theme'), value: 'max-w-xs' },
              { label: __('Small', 'mbn-theme'), value: 'max-w-sm' },
              { label: __('Medium', 'mbn-theme'), value: 'max-w-md' },
              { label: __('Large', 'mbn-theme'), value: 'max-w-lg' },
              { label: __('Extra Large', 'mbn-theme'), value: 'max-w-xl' },
              { label: __('2XL', 'mbn-theme'), value: 'max-w-2xl' },
              { label: __('3XL', 'mbn-theme'), value: 'max-w-3xl' },
              { label: __('4XL (Default)', 'mbn-theme'), value: 'max-w-4xl' },
              { label: __('5XL', 'mbn-theme'), value: 'max-w-5xl' },
              { label: __('6XL', 'mbn-theme'), value: 'max-w-6xl' },
              { label: __('7XL', 'mbn-theme'), value: 'max-w-7xl' }
            ]}
            onChange={(value) => setAttributes({ bottomTextMaxWidth: value })}
            help={__('Set the maximum width for the bottom text', 'mbn-theme')}
          />
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
          
          {/* Top Heading */}
          <div className={`mb-16 md:mb-24 lg:mb-32 text-${topHeadingAlignment}`}>
            <RichText
              tagName="p"
              value={topHeading}
              onChange={(value) => setAttributes({ topHeading: value })}
              placeholder={__('Enter top heading...', 'mbn-theme')}
              className={`text-4xl lg:text-[40px] font-bold font-sofia leading-tight tracking-tight text-mission-text ${topHeadingMaxWidth} mx-auto`}
              allowedFormats={['core/bold', 'core/italic', 'core/underline']}
            />
          </div>

          {/* Middle Content - Grid Layout */}
          {middleContentLayout === 'grid' && (
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
                    className="text-mission-text text-[40px] leading-[1.2] tracking-mission font-bold font-sofia"
                    allowedFormats={[]}
                  />
                </div>
                
                {/* Left Image */}
                {leftImageUrl && (
                  <div className="relative w-full">
                    <img 
                      src={leftImageUrl} 
                      alt={leftImageAlt || ''} 
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
                      alt={rightImageAlt || ''} 
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
                    className="text-mission-text text-[40px] leading-[1.2] tracking-mission font-bold font-sofia"
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
          )}

          {/* Middle Content - Image Only */}
          {middleContentLayout === 'image-only' && middleImageUrl && (
            <div className="w-full">
              <img 
                src={middleImageUrl} 
                alt={middleImageAlt || ''} 
                className="w-full h-auto rounded-2xl shadow-xl object-cover"
              />
            </div>
          )}

          {/* Middle Content - Image and Text Repeater */}
          {middleContentLayout === 'image-text' && (
            <div className="space-y-8">
              {middleItems.map((item, index) => (
                <div 
                  key={item.id}
                  className={`grid grid-cols-1 md:grid-cols-2 gap-8 items-center ${item.reversed ? 'md:flex md:flex-row-reverse' : ''}`}
                >
                  {/* Text */}
                  <div className="text-left">
                    <RichText
                      tagName="p"
                      value={item.text}
                      onChange={(value) => updateItem(index, { text: value })}
                      placeholder={__('Enter text...', 'mbn-theme')}
                      className="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-mission font-bold font-sofia"
                      allowedFormats={[]}
                    />
                  </div>
                  
                  {/* Image */}
                  {item.imageUrl && (
                    <div className="relative w-full">
                      <img 
                        src={item.imageUrl} 
                        alt={item.imageAlt || ''} 
                        className="w-full h-auto rounded-2xl shadow-xl object-cover"
                      />
                    </div>
                  )}
                </div>
              ))}
            </div>
          )}

          {/* Bottom Text */}
          <div className={`mt-16 md:mt-32 space-y-6 text-${bottomTextAlignment}`}>
            <RichText
              tagName="p"
              value={bottomText}
              onChange={(value) => setAttributes({ bottomText: value })}
              placeholder={__('Bottom text...', 'mbn-theme')}
              className={`text-mission-text text-[40px] leading-[1.2] tracking-mission font-bold font-sofia ${bottomTextMaxWidth} mx-auto`}
              allowedFormats={['core/bold', 'core/italic', 'core/underline']}
            />
          </div>

        </div>
      </div>
    </Fragment>
  );
}

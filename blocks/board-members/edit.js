import { useBlockProps, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, TextareaControl, Placeholder, Icon, IconButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Fragment, useState } from '@wordpress/element';
import { DndContext, closestCenter, KeyboardSensor, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { arrayMove, SortableContext, sortableKeyboardCoordinates, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

// Sortable Member Item Component
function SortableMemberItem({ member, index, updateMember, removeMember, duplicateMember }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: `member-${index}` });

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
          <strong>{__('Member', 'mbn-theme')} {index + 1}</strong>
        </div>
        <div style={{ display: 'flex', gap: '8px' }}>
          <IconButton
            icon="admin-page"
            label={__('Duplicate', 'mbn-theme')}
            onClick={() => duplicateMember(index)}
          />
          <IconButton
            icon="trash"
            label={__('Remove', 'mbn-theme')}
            onClick={() => removeMember(index)}
          />
        </div>
      </div>

      <TextControl
        label={__('Name', 'mbn-theme')}
        value={member.name}
        onChange={(value) => updateMember(index, { name: value })}
      />

      <TextControl
        label={__('Position', 'mbn-theme')}
        value={member.position}
        onChange={(value) => updateMember(index, { position: value })}
      />

      <TextareaControl
        label={__('Description', 'mbn-theme')}
        value={member.description}
        onChange={(value) => updateMember(index, { description: value })}
        rows={4}
      />

      <MediaUpload
        onSelect={(media) => {
          updateMember(index, { imageUrl: media.url, imageId: media.id });
        }}
        allowedTypes={['image']}
        value={member.imageId}
        render={({ open }) => (
          <div>
            <Button onClick={open} variant="secondary" style={{ marginTop: '10px' }}>
              {member.imageUrl ? __('Replace Image', 'mbn-theme') : __('Select Image', 'mbn-theme')}
            </Button>
            {member.imageUrl && (
              <img src={member.imageUrl} alt="" style={{ marginTop: '10px', maxWidth: '100%', height: 'auto', borderRadius: '4px' }} />
            )}
          </div>
        )}
      />
    </div>
  );
}

export default function Edit({ attributes, setAttributes }) {
  const { backgroundImageUrl, backgroundImageId, heading, subtext, members } = attributes;

  const blockProps = useBlockProps({
    className: 'relative w-full py-20 md:py-24 lg:py-32 overflow-hidden bg-cream',
    style: backgroundImageUrl ? {
      backgroundImage: `url(${backgroundImageUrl})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    } : {
      backgroundColor: '#F9F5EE'
    }
  });

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  const updateMember = (index, updates) => {
    const updatedMembers = [...members];
    updatedMembers[index] = { ...updatedMembers[index], ...updates };
    setAttributes({ members: updatedMembers });
  };

  const addMember = () => {
    setAttributes({
      members: [...members, { name: '', position: '', description: '', imageUrl: '', imageId: 0 }]
    });
  };

  const removeMember = (index) => {
    const updatedMembers = members.filter((_, i) => i !== index);
    setAttributes({ members: updatedMembers });
  };

  const duplicateMember = (index) => {
    const memberToDuplicate = { ...members[index] };
    const updatedMembers = [
      ...members.slice(0, index + 1),
      memberToDuplicate,
      ...members.slice(index + 1)
    ];
    setAttributes({ members: updatedMembers });
  };

  const handleDragEnd = (event) => {
    const { active, over } = event;

    if (active.id !== over.id) {
      const oldIndex = parseInt(active.id.split('-')[1]);
      const newIndex = parseInt(over.id.split('-')[1]);
      
      setAttributes({
        members: arrayMove(members, oldIndex, newIndex),
      });
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

        <PanelBody title={__('Board Members', 'mbn-theme')} initialOpen={true}>
          <p style={{ marginBottom: '15px', fontSize: '13px', color: '#666' }}>
            {__('Drag and drop to reorder members', 'mbn-theme')}
          </p>
          
          <DndContext
            sensors={sensors}
            collisionDetection={closestCenter}
            onDragEnd={handleDragEnd}
          >
            <SortableContext
              items={members.map((_, index) => `member-${index}`)}
              strategy={verticalListSortingStrategy}
            >
              {members.map((member, index) => (
                <SortableMemberItem
                  key={`member-${index}`}
                  member={member}
                  index={index}
                  updateMember={updateMember}
                  removeMember={removeMember}
                  duplicateMember={duplicateMember}
                />
              ))}
            </SortableContext>
          </DndContext>

          <Button variant="primary" onClick={addMember} style={{ marginTop: '15px' }}>
            {__('+ Add Member', 'mbn-theme')}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div className="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">
          
          {/* Section Header */}
          <div className="text-center mb-12 md:mb-16">
            <RichText
              tagName="h2"
              value={heading}
              onChange={(value) => setAttributes({ heading: value })}
              placeholder={__('Enter heading...', 'mbn-theme')}
              className="font-sofia text-[40px] lg:text-[52px] font-bold leading-[1.1] tracking-[-0.02em] text-black mb-4"
            />
            <RichText
              tagName="p"
              value={subtext}
              onChange={(value) => setAttributes({ subtext: value })}
              placeholder={__('Enter subtext...', 'mbn-theme')}
              className="text-base lg:text-lg font-light leading-relaxed text-dark-text max-w-3xl mx-auto"
            />
          </div>

          {/* Members Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {members.map((member, index) => (
              <div
                key={index}
                className="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300"
              >
                {member.imageUrl ? (
                  <div className="aspect-[4/3] overflow-hidden">
                    <img
                      src={member.imageUrl}
                      alt={member.name}
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                  </div>
                ) : (
                  <div className="aspect-[4/3] bg-gray-300 flex items-center justify-center">
                    <span className="text-gray-600">{__('No image', 'mbn-theme')}</span>
                  </div>
                )}
                
                <div className="p-6">
                  <h3 className="text-xl lg:text-2xl font-bold font-sofia text-black mb-1">
                    {member.name || __('Member name...', 'mbn-theme')}
                  </h3>
                  <p className="text-sm font-medium text-gold mb-4">
                    {member.position || __('Position title...', 'mbn-theme')}
                  </p>
                  <p className="text-sm lg:text-base font-light leading-relaxed text-dark-text">
                    {member.description || __('Description...', 'mbn-theme')}
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

import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { mainHeading, description1, description2, sectionHeading, cards } = attributes;

  const blockProps = useBlockProps({
    className: 'w-full py-16 md:py-24 bg-white'
  });

  const updateCard = (index, key, value) => {
    const updatedCards = [...cards];
    updatedCards[index] = { ...updatedCards[index], [key]: value };
    setAttributes({ cards: updatedCards });
  };

  const updateCardBullet = (cardIndex, bulletIndex, value) => {
    const updatedCards = [...cards];
    const bullets = [...updatedCards[cardIndex].bullets];
    bullets[bulletIndex] = value;
    updatedCards[cardIndex] = { ...updatedCards[cardIndex], bullets };
    setAttributes({ cards: updatedCards });
  };

  const addBullet = (cardIndex) => {
    const updatedCards = [...cards];
    updatedCards[cardIndex].bullets.push('');
    setAttributes({ cards: updatedCards });
  };

  const removeBullet = (cardIndex, bulletIndex) => {
    const updatedCards = [...cards];
    updatedCards[cardIndex].bullets = updatedCards[cardIndex].bullets.filter((_, i) => i !== bulletIndex);
    setAttributes({ cards: updatedCards });
  };

  const bgColorOptions = [
    { label: __('Cream', 'blacklineguardianfund-theme'), value: 'card-cream' },
    { label: __('Gold', 'blacklineguardianfund-theme'), value: 'card-gold' },
    { label: __('Beige', 'blacklineguardianfund-theme'), value: 'card-beige' }
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Header Content', 'blacklineguardianfund-theme')}>
          <TextControl
            label={__('Main Heading', 'blacklineguardianfund-theme')}
            value={mainHeading}
            onChange={(value) => setAttributes({ mainHeading: value })}
          />
          <TextControl
            label={__('Description Paragraph 1', 'blacklineguardianfund-theme')}
            value={description1}
            onChange={(value) => setAttributes({ description1: value })}
            help={__('First description paragraph', 'blacklineguardianfund-theme')}
          />
          <TextControl
            label={__('Description Paragraph 2', 'blacklineguardianfund-theme')}
            value={description2}
            onChange={(value) => setAttributes({ description2: value })}
            help={__('Second description paragraph', 'blacklineguardianfund-theme')}
          />
          <TextControl
            label={__('Section Heading', 'blacklineguardianfund-theme')}
            value={sectionHeading}
            onChange={(value) => setAttributes({ sectionHeading: value })}
          />
        </PanelBody>

        {cards.map((card, cardIndex) => (
          <PanelBody 
            key={cardIndex} 
            title={`${__('Card', 'blacklineguardianfund-theme')} ${cardIndex + 1}: ${card.title}`}
            initialOpen={false}
          >
            <TextControl
              label={__('Card Title', 'blacklineguardianfund-theme')}
              value={card.title}
              onChange={(value) => updateCard(cardIndex, 'title', value)}
            />
            <TextControl
              label={__('Amount', 'blacklineguardianfund-theme')}
              value={card.amount}
              onChange={(value) => updateCard(cardIndex, 'amount', value)}
            />
            <TextControl
              label={__('Description', 'blacklineguardianfund-theme')}
              value={card.description}
              onChange={(value) => updateCard(cardIndex, 'description', value)}
            />
            <SelectControl
              label={__('Background Color', 'blacklineguardianfund-theme')}
              value={card.bgColor}
              options={bgColorOptions}
              onChange={(value) => updateCard(cardIndex, 'bgColor', value)}
            />
            
            <hr style={{ margin: '16px 0' }} />
            <strong>{__('Bullet Points', 'blacklineguardianfund-theme')}</strong>
            {card.bullets.map((bullet, bulletIndex) => (
              <div key={bulletIndex} style={{ marginTop: '8px', display: 'flex', gap: '8px' }}>
                <TextControl
                  value={bullet}
                  onChange={(value) => updateCardBullet(cardIndex, bulletIndex, value)}
                  style={{ flex: 1 }}
                />
                <Button
                  isDestructive
                  isSmall
                  onClick={() => removeBullet(cardIndex, bulletIndex)}
                >
                  {__('Remove', 'blacklineguardianfund-theme')}
                </Button>
              </div>
            ))}
            <Button
              isSecondary
              isSmall
              onClick={() => addBullet(cardIndex)}
              style={{ marginTop: '8px' }}
            >
              {__('Add Bullet', 'blacklineguardianfund-theme')}
            </Button>

            <hr style={{ margin: '16px 0' }} />
            <TextControl
              label={__('Button Text', 'blacklineguardianfund-theme')}
              value={card.buttonText}
              onChange={(value) => updateCard(cardIndex, 'buttonText', value)}
            />
            <TextControl
              label={__('Button URL', 'blacklineguardianfund-theme')}
              value={card.buttonUrl}
              onChange={(value) => updateCard(cardIndex, 'buttonUrl', value)}
            />
          </PanelBody>
        ))}
      </InspectorControls>

      <div {...blockProps}>
        <div className="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
          
          {/* Header Section */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '32px', marginBottom: '64px' }}>
            <div>
              <RichText
                tagName="h2"
                value={mainHeading}
                onChange={(value) => setAttributes({ mainHeading: value })}
                placeholder={__('Enter main heading...', 'blacklineguardianfund-theme')}
                style={{ fontSize: '48px', fontWeight: 'bold', lineHeight: '1.2', color: '#25272B' }}
              />
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
              <p style={{ fontSize: '16px', lineHeight: '1.6', color: '#25272B' }}>{description1}</p>
              <p style={{ fontSize: '16px', lineHeight: '1.6', color: '#25272B' }}>{description2}</p>
            </div>
          </div>

          {/* Section Heading */}
          <div style={{ display: 'flex', alignItems: 'center', gap: '24px', marginBottom: '32px' }}>
            <h3 style={{ fontSize: '32px', fontWeight: 'bold', color: '#25272B', whiteSpace: 'nowrap', margin: 0 }}>
              {sectionHeading}
            </h3>
            <div style={{ flexGrow: 1, height: '1px', backgroundColor: '#CEB270' }}></div>
          </div>

          {/* Donation Cards */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '32px' }}>
            {cards.map((card, index) => (
              <div
                key={index}
                style={{
                  backgroundColor: card.bgColor === 'card-cream' ? '#F5F1E8' : card.bgColor === 'card-gold' ? '#FFF4D9' : '#F8F5F0',
                  borderRadius: '24px',
                  padding: '32px',
                  border: '1px solid rgba(0,0,0,0.15)',
                  display: 'flex',
                  flexDirection: 'column'
                }}
              >
                <h4 style={{ fontSize: '20px', fontWeight: '600', marginBottom: '16px', color: '#25272B' }}>
                  {card.title}
                </h4>
                <p style={{ fontSize: '56px', fontWeight: 'bold', color: '#B89352', marginBottom: '24px' }}>
                  {card.amount}
                </p>
                <div style={{ height: '1px', backgroundColor: 'rgba(0,0,0,0.15)', marginBottom: '24px' }}></div>
                <p style={{ fontSize: '16px', lineHeight: '1.6', marginBottom: '24px', color: '#25272B' }}>
                  {card.description}
                </p>
                <ul style={{ listStyle: 'none', padding: 0, marginBottom: '32px', flexGrow: 1 }}>
                  {card.bullets.map((bullet, i) => (
                    <li key={i} style={{ display: 'flex', gap: '12px', marginBottom: '12px', alignItems: 'flex-start' }}>
                      <span style={{ color: '#7CAA6D', fontSize: '20px' }}>✓</span>
                      <span style={{ fontSize: '16px', color: '#25272B' }}>{bullet}</span>
                    </li>
                  ))}
                </ul>
                <div style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '8px',
                  height: '48px',
                  padding: '0 24px',
                  borderRadius: '24px',
                  background: 'linear-gradient(to bottom, #FCE5B0, #B89352)',
                  color: '#6B4502',
                  fontWeight: 'bold',
                  fontSize: '14px',
                  textTransform: 'uppercase'
                }}>
                  {card.buttonText}
                </div>
              </div>
            ))}
          </div>

        </div>
      </div>
    </>
  );
}

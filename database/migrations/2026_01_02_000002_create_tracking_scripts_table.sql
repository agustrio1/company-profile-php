-- Migration: 2025_01_02_000002_create_tracking_scripts_table.sql

-- =====================================================
-- Table: tracking_scripts
-- Purpose: Mengatur tracking scripts (Analytics, Pixels, dll)
-- =====================================================
CREATE TABLE tracking_scripts (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'google_analytics', 'facebook_pixel', 'google_tag_manager', 'hotjar', 'custom'
    tracking_id VARCHAR(255), -- GA4 Measurement ID, FB Pixel ID, GTM ID, dll
    script_head TEXT, -- Script untuk <head>
    script_body TEXT, -- Script untuk <body>
    script_footer TEXT, -- Script untuk sebelum </body>
    placement VARCHAR(50) DEFAULT 'all', -- 'all', 'specific_pages', 'exclude_pages'
    include_pages TEXT, -- JSON array of page slugs to include
    exclude_pages TEXT, -- JSON array of page slugs to exclude
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_tracking_type ON tracking_scripts(type);
CREATE INDEX idx_tracking_active ON tracking_scripts(is_active);
CREATE INDEX idx_tracking_sort ON tracking_scripts(sort_order);

COMMENT ON TABLE tracking_scripts IS 'Tracking scripts untuk analytics dan pixels';
COMMENT ON COLUMN tracking_scripts.type IS 'Tipe: google_analytics, facebook_pixel, google_tag_manager, hotjar, custom';
COMMENT ON COLUMN tracking_scripts.placement IS 'Penempatan: all (semua halaman), specific_pages, exclude_pages';
COMMENT ON COLUMN tracking_scripts.include_pages IS 'JSON array halaman yang diinclude (jika placement = specific_pages)';
COMMENT ON COLUMN tracking_scripts.exclude_pages IS 'JSON array halaman yang diexclude (jika placement = exclude_pages)';

-- =====================================================
-- Table: conversion_events
-- Purpose: Track conversion events untuk analytics
-- =====================================================
CREATE TABLE conversion_events (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    event_type VARCHAR(50) NOT NULL, -- 'page_view', 'form_submit', 'button_click', 'purchase', 'lead', 'custom'
    trigger_element VARCHAR(255), -- CSS selector atau ID element yang trigger event
    event_category VARCHAR(100),
    event_action VARCHAR(100),
    event_label VARCHAR(255),
    event_value DECIMAL(10,2),
    custom_code TEXT, -- Custom JavaScript code untuk event
    page_slug VARCHAR(100), -- Halaman spesifik (null = semua halaman)
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_conversion_type ON conversion_events(event_type);
CREATE INDEX idx_conversion_page ON conversion_events(page_slug);
CREATE INDEX idx_conversion_active ON conversion_events(is_active);

COMMENT ON TABLE conversion_events IS 'Conversion events untuk tracking';
COMMENT ON COLUMN conversion_events.trigger_element IS 'CSS selector element yang trigger event (contoh: #contact-form, .cta-button)';

-- =====================================================
-- Table: site_settings
-- Purpose: General site settings (termasuk tracking keys)
-- =====================================================
CREATE TABLE site_settings (
    id VARCHAR(26) PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type VARCHAR(50) DEFAULT 'text', -- 'text', 'textarea', 'boolean', 'json'
    category VARCHAR(50) DEFAULT 'general', -- 'general', 'tracking', 'seo', 'appearance', 'advanced'
    description TEXT,
    is_public BOOLEAN DEFAULT false, -- Apakah value bisa diakses public
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_settings_key ON site_settings(setting_key);
CREATE INDEX idx_settings_category ON site_settings(category);

COMMENT ON TABLE site_settings IS 'General site settings';
COMMENT ON COLUMN site_settings.is_public IS 'Apakah setting ini bisa diakses dari frontend';

-- =====================================================
-- Seed default tracking scripts templates
-- =====================================================

-- Google Analytics 4 Template
INSERT INTO tracking_scripts (id, name, type, tracking_id, script_head, is_active, sort_order) VALUES
('01JGYYY1XXXXXXXXXXXX0001', 'Google Analytics 4', 'google_analytics', NULL, 
'<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{TRACKING_ID}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(''js'', new Date());
  gtag(''config'', ''{{TRACKING_ID}}'');
</script>', 
false, 1);

-- Facebook Pixel Template
INSERT INTO tracking_scripts (id, name, type, tracking_id, script_head, is_active, sort_order) VALUES
('01JGYYY2XXXXXXXXXXXX0002', 'Facebook Pixel', 'facebook_pixel', NULL,
'<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=''2.0'';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,''script'',
  ''https://connect.facebook.net/en_US/fbevents.js'');
  fbq(''init'', ''{{TRACKING_ID}}'');
  fbq(''track'', ''PageView'');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id={{TRACKING_ID}}&ev=PageView&noscript=1"
/></noscript>',
false, 2);

-- Google Tag Manager Template
INSERT INTO tracking_scripts (id, name, type, tracking_id, script_head, script_body, is_active, sort_order) VALUES
('01JGYYY3XXXXXXXXXXXX0003', 'Google Tag Manager', 'google_tag_manager', NULL,
'<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({''gtm.start'':
new Date().getTime(),event:''gtm.js''});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!=''dataLayer''?''&l=''+l:'''';j.async=true;j.src=
''https://www.googletagmanager.com/gtm.js?id=''+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,''script'',''dataLayer'',''{{TRACKING_ID}}'');</script>',
'<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{TRACKING_ID}}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
false, 3);

-- Hotjar Template
INSERT INTO tracking_scripts (id, name, type, tracking_id, script_head, is_active, sort_order) VALUES
('01JGYYY4XXXXXXXXXXXX0004', 'Hotjar', 'hotjar', NULL,
'<!-- Hotjar Tracking Code -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:{{TRACKING_ID}},hjsv:6};
        a=o.getElementsByTagName(''head'')[0];
        r=o.createElement(''script'');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,''https://static.hotjar.com/c/hotjar-'',''.js?sv='');
</script>',
false, 4);

-- =====================================================
-- Seed default site settings
-- =====================================================
INSERT INTO site_settings (id, setting_key, setting_value, setting_type, category, description, is_public) VALUES
('01JGZZZ1XXXXXXXXXXXX0001', 'ga4_measurement_id', NULL, 'text', 'tracking', 'Google Analytics 4 Measurement ID (G-XXXXXXXXXX)', false),
('01JGZZZ2XXXXXXXXXXXX0002', 'fb_pixel_id', NULL, 'text', 'tracking', 'Facebook Pixel ID', false),
('01JGZZZ3XXXXXXXXXXXX0003', 'gtm_container_id', NULL, 'text', 'tracking', 'Google Tag Manager Container ID (GTM-XXXXXXX)', false),
('01JGZZZ4XXXXXXXXXXXX0004', 'hotjar_site_id', NULL, 'text', 'tracking', 'Hotjar Site ID', false),
('01JGZZZ5XXXXXXXXXXXX0005', 'enable_tracking', 'false', 'boolean', 'tracking', 'Enable/Disable all tracking scripts', true),
('01JGZZZ6XXXXXXXXXXXX0006', 'site_name', NULL, 'text', 'general', 'Site Name', true),
('01JGZZZ7XXXXXXXXXXXX0007', 'site_tagline', NULL, 'text', 'general', 'Site Tagline', true),
('01JGZZZ8XXXXXXXXXXXX0008', 'contact_email', NULL, 'text', 'general', 'Contact Email', true),
('01JGZZZ9XXXXXXXXXXXX0009', 'contact_phone', NULL, 'text', 'general', 'Contact Phone', true),
('01JGZZZAXXXXXXXXXXXX0010', 'google_maps_api_key', NULL, 'text', 'advanced', 'Google Maps API Key', false);

-- =====================================================
-- Record migration
-- =====================================================
INSERT INTO migrations (migration, batch) 
VALUES ('2025_01_02_000002_create_tracking_scripts_table.sql', 2)
ON CONFLICT (migration) DO NOTHING;
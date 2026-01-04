-- Migration: 2025_01_02_000001_create_hero_and_maps_tables.sql

-- =====================================================
-- Table: hero_sections
-- Purpose: Mengatur hero section untuk setiap halaman
-- =====================================================
CREATE TABLE hero_sections (
    id VARCHAR(26) PRIMARY KEY,
    page_slug VARCHAR(100) NOT NULL UNIQUE, -- 'home', 'about', 'services', 'contact', 'blog'
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    description TEXT,
    background_image VARCHAR(255),
    background_type VARCHAR(20) DEFAULT 'gradient', -- 'gradient', 'image', 'video', 'solid'
    background_color VARCHAR(50) DEFAULT 'from-brand to-brand-strong',
    cta_primary_text VARCHAR(100),
    cta_primary_link VARCHAR(255),
    cta_secondary_text VARCHAR(100),
    cta_secondary_link VARCHAR(255),
    text_alignment VARCHAR(20) DEFAULT 'left', -- 'left', 'center', 'right'
    overlay_opacity DECIMAL(3,2) DEFAULT 0.5,
    height VARCHAR(20) DEFAULT 'large', -- 'small', 'medium', 'large', 'full'
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_hero_page_slug ON hero_sections(page_slug);
CREATE INDEX idx_hero_active ON hero_sections(is_active);

COMMENT ON TABLE hero_sections IS 'Hero sections untuk setiap halaman website';
COMMENT ON COLUMN hero_sections.page_slug IS 'Slug halaman: home, about, services, contact, blog, dll';
COMMENT ON COLUMN hero_sections.background_type IS 'Tipe background: gradient, image, video, solid';
COMMENT ON COLUMN hero_sections.height IS 'Tinggi hero: small (400px), medium (600px), large (800px), full (100vh)';

-- =====================================================
-- Table: page_maps
-- Purpose: Mengatur Google Maps untuk halaman tertentu
-- =====================================================
CREATE TABLE page_maps (
    id VARCHAR(26) PRIMARY KEY,
    page_slug VARCHAR(100) NOT NULL, -- 'contact', 'about', 'service-detail'
    title VARCHAR(255),
    description TEXT,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    zoom_level INTEGER DEFAULT 15,
    marker_title VARCHAR(255),
    marker_description TEXT,
    map_style VARCHAR(50) DEFAULT 'roadmap', -- 'roadmap', 'satellite', 'hybrid', 'terrain'
    show_marker BOOLEAN DEFAULT true,
    show_info_window BOOLEAN DEFAULT true,
    map_height VARCHAR(20) DEFAULT 'medium', -- 'small' (300px), 'medium' (450px), 'large' (600px)
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_page_maps_slug ON page_maps(page_slug);
CREATE INDEX idx_page_maps_active ON page_maps(is_active);

COMMENT ON TABLE page_maps IS 'Konfigurasi Google Maps untuk halaman tertentu';
COMMENT ON COLUMN page_maps.page_slug IS 'Slug halaman tempat map ditampilkan';
COMMENT ON COLUMN page_maps.map_style IS 'Style map: roadmap, satellite, hybrid, terrain';

-- =====================================================
-- Table: page_sections
-- Purpose: Section custom untuk halaman (features, benefits, dll)
-- =====================================================
CREATE TABLE page_sections (
    id VARCHAR(26) PRIMARY KEY,
    page_slug VARCHAR(100) NOT NULL,
    section_type VARCHAR(50) NOT NULL, -- 'features', 'benefits', 'testimonials', 'stats', 'cta', 'custom'
    title VARCHAR(255),
    subtitle TEXT,
    content TEXT,
    background_color VARCHAR(50),
    text_color VARCHAR(50),
    icon VARCHAR(255),
    image VARCHAR(255),
    link_text VARCHAR(100),
    link_url VARCHAR(255),
    layout VARCHAR(50) DEFAULT 'default', -- 'default', 'cards', 'list', 'grid', 'columns'
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_page_sections_slug ON page_sections(page_slug);
CREATE INDEX idx_page_sections_type ON page_sections(section_type);
CREATE INDEX idx_page_sections_active ON page_sections(is_active);
CREATE INDEX idx_page_sections_sort ON page_sections(page_slug, sort_order);

COMMENT ON TABLE page_sections IS 'Section custom untuk setiap halaman';
COMMENT ON COLUMN page_sections.section_type IS 'Tipe section: features, benefits, testimonials, stats, cta, custom';

-- =====================================================
-- Table: page_section_items
-- Purpose: Items dalam section (untuk list, cards, dll)
-- =====================================================
CREATE TABLE page_section_items (
    id VARCHAR(26) PRIMARY KEY,
    section_id VARCHAR(26) NOT NULL REFERENCES page_sections(id) ON DELETE CASCADE,
    title VARCHAR(255),
    description TEXT,
    icon VARCHAR(255),
    image VARCHAR(255),
    link_url VARCHAR(255),
    metadata JSONB, -- Data tambahan (stats, testimonial author, dll)
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_section_items_section ON page_section_items(section_id);
CREATE INDEX idx_section_items_sort ON page_section_items(section_id, sort_order);

COMMENT ON TABLE page_section_items IS 'Items dalam page section (list items, cards, features, dll)';
COMMENT ON COLUMN page_section_items.metadata IS 'Data tambahan dalam format JSON';

-- =====================================================
-- Seed default hero sections
-- =====================================================
INSERT INTO hero_sections (id, page_slug, title, subtitle, description, background_type, height, is_active, sort_order) VALUES
('01JGXXX1XXXXXXXXXXXXXXXXXX', 'home', 'Selamat Datang', 'Di Portal Kami', 'Solusi terbaik untuk kebutuhan bisnis Anda dengan layanan profesional dan berkualitas tinggi', 'gradient', 'large', true, 1),
('01JGXXX2XXXXXXXXXXXXXXXXXX', 'about', 'Tentang Kami', 'Kenali Lebih Dekat', 'Kami adalah perusahaan yang berdedikasi memberikan layanan terbaik untuk kesuksesan bisnis Anda', 'gradient', 'medium', true, 1),
('01JGXXX3XXXXXXXXXXXXXXXXXX', 'services', 'Layanan Kami', 'Solusi Lengkap', 'Berbagai layanan profesional untuk mendukung pertumbuhan bisnis Anda', 'gradient', 'medium', true, 1),
('01JGXXX4XXXXXXXXXXXXXXXXXX', 'contact', 'Hubungi Kami', 'Mari Berbincang', 'Kami siap membantu Anda. Hubungi kami untuk konsultasi gratis', 'gradient', 'small', true, 1),
('01JGXXX5XXXXXXXXXXXXXXXXXX', 'blog', 'Blog & Artikel', 'Informasi Terkini', 'Baca artikel dan berita terbaru dari kami', 'gradient', 'small', true, 1);

-- =====================================================
-- Record migration
-- =====================================================
INSERT INTO migrations (migration, batch) 
VALUES ('2025_01_02_000001_create_hero_and_maps_tables.sql', 2)
ON CONFLICT (migration) DO NOTHING;
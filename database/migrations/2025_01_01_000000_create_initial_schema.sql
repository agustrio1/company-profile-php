-- Migration: Create Company Profile System with ULID
-- Description: Database schema for company profile with blog, team, services, and RBAC
-- ULID Format: 26 characters (VARCHAR(26))

-- =====================================================
-- 0. MIGRATIONS TABLE (Migration Tracking)
-- =====================================================
CREATE TABLE migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) UNIQUE NOT NULL,
    batch INTEGER NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_migrations_batch ON migrations(batch);

COMMENT ON TABLE migrations IS 'Tracks database migrations for version control and rollback capability';

-- =====================================================
-- 1. COMPANY TABLE
-- =====================================================
CREATE TABLE company (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    vision TEXT,
    mission TEXT,
    logo VARCHAR(255),
    founded_year INTEGER,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_company_slug ON company(slug);

-- =====================================================
-- 2. USERS TABLE
-- =====================================================
CREATE TABLE users (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);

-- =====================================================
-- 3. SESSIONS TABLE
-- =====================================================
CREATE TABLE sessions (
    id VARCHAR(26) PRIMARY KEY,
    user_id VARCHAR(26) NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_token ON sessions(token);
CREATE INDEX idx_sessions_expires_at ON sessions(expires_at);

-- =====================================================
-- 4. PASSWORD_RESETS TABLE
-- =====================================================
CREATE TABLE password_resets (
    id VARCHAR(26) PRIMARY KEY,
    user_id VARCHAR(26) NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_password_resets_user_id ON password_resets(user_id);
CREATE INDEX idx_password_resets_token ON password_resets(token);
CREATE INDEX idx_password_resets_expires_at ON password_resets(expires_at);

-- =====================================================
-- 5. ROLES TABLE
-- =====================================================
CREATE TABLE roles (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT
);

CREATE INDEX idx_roles_name ON roles(name);

-- =====================================================
-- 6. PERMISSIONS TABLE
-- =====================================================
CREATE TABLE permissions (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT
);

CREATE INDEX idx_permissions_name ON permissions(name);

-- =====================================================
-- 7. ROLE_USER TABLE (Many-to-Many)
-- =====================================================
CREATE TABLE role_user (
    user_id VARCHAR(26) NOT NULL,
    role_id VARCHAR(26) NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE INDEX idx_role_user_user_id ON role_user(user_id);
CREATE INDEX idx_role_user_role_id ON role_user(role_id);

-- =====================================================
-- 8. PERMISSION_ROLE TABLE (Many-to-Many)
-- =====================================================
CREATE TABLE permission_role (
    role_id VARCHAR(26) NOT NULL,
    permission_id VARCHAR(26) NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

CREATE INDEX idx_permission_role_role_id ON permission_role(role_id);
CREATE INDEX idx_permission_role_permission_id ON permission_role(permission_id);

-- =====================================================
-- 9. SERVICES TABLE
-- =====================================================
CREATE TABLE services (
    id VARCHAR(26) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_services_slug ON services(slug);
CREATE INDEX idx_services_is_featured ON services(is_featured);

-- =====================================================
-- 10. TEAMS TABLE
-- =====================================================
CREATE TABLE teams (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

-- =====================================================
-- 11. EMPLOYEES TABLE
-- =====================================================
CREATE TABLE employees (
    id VARCHAR(26) PRIMARY KEY,
    team_id VARCHAR(26),
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    photo VARCHAR(255),
    bio TEXT,
    sort_order INTEGER DEFAULT 0,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL
);

CREATE INDEX idx_employees_team_id ON employees(team_id);
CREATE INDEX idx_employees_sort_order ON employees(sort_order);

-- =====================================================
-- 12. BLOG_CATEGORIES TABLE
-- =====================================================
CREATE TABLE blog_categories (
    id VARCHAR(26) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT
);

CREATE INDEX idx_blog_categories_slug ON blog_categories(slug);

-- =====================================================
-- 13. BLOGS TABLE
-- =====================================================

-- Create ENUM type for blog status
CREATE TYPE blog_status AS ENUM ('draft', 'published', 'archived');

CREATE TABLE blogs (
    id VARCHAR(26) PRIMARY KEY,
    category_id VARCHAR(26),
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT,
    thumbnail VARCHAR(255),
    author_id VARCHAR(26),
    published_at TIMESTAMP,
    status blog_status DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_blogs_slug ON blogs(slug);
CREATE INDEX idx_blogs_category_id ON blogs(category_id);
CREATE INDEX idx_blogs_author_id ON blogs(author_id);
CREATE INDEX idx_blogs_status ON blogs(status);
CREATE INDEX idx_blogs_published_at ON blogs(published_at);

-- =====================================================
-- 14. BLOG_IMAGES TABLE
-- =====================================================
CREATE TABLE blog_images (
    id VARCHAR(26) PRIMARY KEY,
    blog_id VARCHAR(26) NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
);

CREATE INDEX idx_blog_images_blog_id ON blog_images(blog_id);
CREATE INDEX idx_blog_images_sort_order ON blog_images(sort_order);

-- =====================================================
-- 15. SEO_META TABLE (Polymorphic)
-- =====================================================
CREATE TABLE seo_meta (
    id VARCHAR(26) PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    model_id VARCHAR(26) NOT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT
);

CREATE INDEX idx_seo_meta_model_id ON seo_meta(model, model_id);

-- =====================================================
-- TRIGGERS FOR UPDATED_AT
-- =====================================================

-- Function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Apply trigger to tables with updated_at
CREATE TRIGGER update_company_updated_at
    BEFORE UPDATE ON company
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_services_updated_at
    BEFORE UPDATE ON services
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_blogs_updated_at
    BEFORE UPDATE ON blogs
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- =====================================================
-- COMMENTS FOR DOCUMENTATION
-- =====================================================

COMMENT ON TABLE company IS 'Stores company profile information';
COMMENT ON TABLE users IS 'System users table';
COMMENT ON TABLE sessions IS 'User session management for authentication';
COMMENT ON TABLE password_resets IS 'Password reset tokens for forgot password functionality';
COMMENT ON TABLE roles IS 'User roles for RBAC';
COMMENT ON TABLE permissions IS 'Permissions for RBAC';
COMMENT ON TABLE role_user IS 'Many-to-many relationship between users and roles';
COMMENT ON TABLE permission_role IS 'Many-to-many relationship between roles and permissions';
COMMENT ON TABLE services IS 'Company services/products';
COMMENT ON TABLE teams IS 'Company teams/departments';
COMMENT ON TABLE employees IS 'Team members';
COMMENT ON TABLE blog_categories IS 'Blog post categories';
COMMENT ON TABLE blogs IS 'Blog posts';
COMMENT ON TABLE blog_images IS 'Additional images for blog posts';
COMMENT ON TABLE seo_meta IS 'SEO metadata for various models (polymorphic)';

-- =====================================================
-- INSERT MIGRATION RECORD
-- =====================================================
INSERT INTO migrations (migration, batch) 
VALUES ('2025_01_01_000000_create_initial_schema', 1);
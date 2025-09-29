<?php

// ===============================================
// Block Template: Research Filter by Category + post_sector
// with ACF toggles
// ===============================================

// Set block ID and classes.
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'research-filter'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align'])     ? ' align' . $block['align'] : '');

// ACF toggles (true/false)
$show_filter  = (bool) get_field('filters');
$show_search  = (bool) get_field('search');
$show_cat     = (bool) get_field('category_dd');
$show_tags   = (bool) get_field('tags_dd');
$show_year   = (bool) get_field('year_dd');
$limit_number = get_field('limit_number');

// Get terms
$categories = get_terms([
    'taxonomy'   => 'external-research-category',
    'hide_empty' => true,
]);

$cat_terms_data = array_map(function ($cat) {
    return [
        'slug' => $cat->slug,
        'name' => html_entity_decode($cat->name),
    ];
}, $categories);


$tags = get_tags(['hide_empty' => true]);
$tag_terms_data = array_map(fn($t) => ['slug' => $t->slug, 'name' => html_entity_decode($t->name)], $tags);

// Get years from posts
global $wpdb;
$years = $wpdb->get_col("
	SELECT DISTINCT YEAR(post_date) 
	FROM {$wpdb->posts} 
	WHERE post_type = 'external-research' 
	  AND post_status = 'publish' 
	ORDER BY post_date DESC
");
$year_terms_data = array_map(fn($y) => ['slug' => $y, 'name' => (string) $y], $years);


if (is_admin()) {
    echo '<p><strong>Research Filter Block</strong></p>';
    return;
}
?>

<div
    class="<?= esc_attr($className) ?>"
    x-data="researchApp({
	  catTerms: <?= htmlspecialchars(json_encode($cat_terms_data)) ?>,
	  tagTerms: <?= htmlspecialchars(json_encode($tag_terms_data)) ?>,
	  yearTerms: <?= htmlspecialchars(json_encode($year_terms_data)) ?>,
      limitNumber: <?= $limit_number ?: 'null' ?>,
    })"
    x-init="init()"
    x-cloak>

    <?php if ($show_filter): ?>
        <div class="research-filter-controls">

            <div class="inner">
                <!-- <span class="research-filter-label">
                    Filter by
                </span> -->

                <?php if ($show_cat): ?>
                    <div class="tabs type">
                        <ul>
                            <li :class="{ 'active': filter.category === '' }" @click="selectFilter('category', '')">All</li>
                            <template x-for="term in catTerms" :key="term.slug">
                                <li
                                    :class="{ 'active': filter.category === term.slug }"
                                    @click="selectFilter('category', term.slug)"
                                    x-text="term.name">
                                </li>
                            </template>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($show_tags): ?>
                    <div class="dropdown">
                        <button @click="toggleDropdown('tag')" type="button" class="dropdown-toggle">
                            <span x-text="dropdownText('tag')"></span>
                        </button>
                        <ul x-show="dropdownOpen.tag" @click.away="dropdownOpen.tag = false" x-transition class="dropdown-menu">
                            <li @click="selectFilter('tag', '')" class="dropdown-item">View all</li>
                            <template x-for="term in tagTerms" :key="term.slug">
                                <li @click="selectFilter('tag', term.slug)" class="dropdown-item" x-text="term.name"></li>
                            </template>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($show_year): ?>
                    <div class="dropdown">
                        <button @click="toggleDropdown('year')" type="button" class="dropdown-toggle">
                            <span x-text="dropdownText('year')"></span>
                        </button>
                        <ul x-show="dropdownOpen.year" @click.away="dropdownOpen.year = false" x-transition class="dropdown-menu">
                            <li @click="selectFilter('year', '')" class="dropdown-item">View all</li>
                            <template x-for="term in yearTerms" :key="term.slug">
                                <li @click="selectFilter('year', term.slug)" class="dropdown-item" x-text="term.name"></li>
                            </template>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($show_search): ?>
                    <div class="search-bar">
                        <input type="text" x-model="searchQuery" placeholder="Search external research" autocomplete="off" />
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="research-listing" :class="{ 'limited': limitNumber }">
        <template x-for="item in researchWithSeparators()" :key="item.id">
            <div
                :class="item.isSeparator ? 'row-separator' : 'post-item'"
                x-html="item.isSeparator ? '' : item.content">
            </div>
        </template>
    </div>

    <div x-show="noResults && !loading" class="no-results">
        <h5>Sorry</h5>
        <p>Your search for '<span x-text="searchQuery"></span>' returned 0 results.</p>
        <p>We update our news & views regularly, so be sure to check back again soon.</p>
    </div>
    <?php if (!$limit_number): ?>
        <template x-if="!allLoaded && posts.length > 0">
            <div class="wp-block-button">
                <button class="wp-block-button__link"
                    @click="loadMore()"
                    x-text="loading ? 'Loading...' : 'Load more'"></button>
            </div>
        </template>
    <?php endif; ?>
</div>

<script>
    function researchApp(data = {}) {
        return {
            posts: [],
            offset: 0,
            postsPerPage: 6,
            loading: false,
            limitNumber: data.limitNumber || null,
            filter: {
                category: '',
                tag: '',
                year: ''
            },
            searchQuery: '',
            noResults: false,
            allLoaded: false,
            dropdownOpen: {
                category: false,
                tag: false,
                year: false
            },
            catTerms: data.catTerms || [],
            tagTerms: data.tagTerms || [],
            yearTerms: data.yearTerms || [],
            debounceTimeout: null,

            researchWithSeparators() {
                const result = [];
                this.posts.forEach((post, index) => {
                    result.push(post);
                    // Add separator after every 3rd item (but not after the last item)
                    if ((index + 1) % 3 === 0 && index < this.posts.length - 1) {
                        result.push({
                            id: `separator-${Math.floor(index / 3)}`,
                            isSeparator: true,
                            content: ''
                        });
                    }
                });

                // Add a separator at the very end
                if (this.posts.length > 0) {
                    result.push({
                        id: `separator-end`,
                        isSeparator: true,
                        content: ''
                    });
                }

                return result;
            },

            dropdownText(type) {
                const map = {
                    // category: { label: 'All topics', prefix: 'Topic', terms: this.catTerms },
                    // tag:      { label: 'All tags',    prefix: 'Tag',   terms: this.tagTerms },
                    // year:     { label: 'All years',   prefix: 'Year',  terms: this.yearTerms },
                    category: {
                        label: 'All topics',
                        prefix: '',
                        terms: this.catTerms
                    },
                    tag: {
                        label: 'All tags',
                        prefix: '',
                        terms: this.tagTerms
                    },
                    year: {
                        label: 'All years',
                        prefix: '',
                        terms: this.yearTerms
                    },
                };
                const config = map[type];
                if (!config) return '';
                const val = this.filter[type];
                if (!val) return config.label;
                const match = config.terms.find(t => t.slug === val);
                //return match ? `${config.prefix}: ${match.name}` : config.label;
                return match ? `${match.name}` : config.label;
            },

            toggleDropdown(type) {
                this.dropdownOpen[type] = !this.dropdownOpen[type];
            },

            selectFilter(type, val) {
                if (!val) return this.resetFilter();
                this.filter[type] = val;
                this.dropdownOpen[type] = false;
                this.offset = 0;
                this.allLoaded = false;
                this.loadResearch(true, 5);
                this.updateUrl();
            },

            resetFilter() {
                this.filter = {
                    category: '',
                    tag: '',
                    year: ''
                };
                this.searchQuery = '';
                Object.keys(this.dropdownOpen).forEach(k => this.dropdownOpen[k] = false);
                this.offset = 0;
                this.allLoaded = false;
                this.loadResearch(true, 5);
                this.updateUrl();
            },

            updateUrl() {
                const url = new URL(window.location.href);
                ['category', 'tag', 'year'].forEach(k => {
                    if (this.filter[k]) url.searchParams.set(k, this.filter[k]);
                    else url.searchParams.delete(k);
                });
                history.pushState(null, '', url);
            },

            init() {
                const params = new URLSearchParams(window.location.search);
                ['category', 'tag', 'year'].forEach(k => {
                    if (params.get(k)) this.filter[k] = params.get(k);
                });

                if (this.limitNumber) {
                    this.postsPerPage = this.limitNumber;
                }

                this.loadResearch(true, 6);

                if (!this.limitNumber) {
                    this.$watch('searchQuery', val => {
                        clearTimeout(this.debounceTimeout);
                        this.debounceTimeout = setTimeout(() => {
                            this.offset = 0;
                            this.allLoaded = false;
                            this.loadResearch(true, 6);
                        }, 500);
                    });
                };
            },

            loadResearch(reset = false, customLimit = null) {
                if (this.loading) return;
                this.loading = true;
                const fd = new FormData();
                fd.append('action', 'load_wp_research');
                fd.append('offset', reset ? 0 : this.offset);

                if (this.limitNumber) {
                    fd.append('posts_per_page', this.limitNumber);
                    fd.append('limit_number', this.limitNumber); // 🔑 so backend knows to ignore offset
                } else {
                    fd.append('posts_per_page', customLimit ?? this.postsPerPage);
                }

                fd.append('category', this.filter.category);
                fd.append('tag', this.filter.tag);
                fd.append('year', this.filter.year);
                fd.append('search', this.searchQuery);

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        credentials: 'same-origin',
                        body: fd
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (reset) {
                            this.posts = data.posts;
                            this.offset = data.posts.length;
                        } else {
                            this.posts = this.posts.concat(data.posts);
                            this.offset += data.posts.length;
                        }

                        this.noResults = this.posts.length === 0;

                        if (data.posts.length < (customLimit ?? this.postsPerPage)) {
                            this.allLoaded = true;
                        }

                        this.loading = false;

                        this.$nextTick(() => {
                            if (typeof GLightbox !== 'undefined') {
                                GLightbox({
                                    selector: '.glightbox'
                                });
                            }
                            if (typeof equalizeHeights === 'function') {
                                setTimeout(equalizeHeights, 50);
                            }
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    });
            },

            loadMore() {
                this.loadResearch();
            }
        }
    }
</script>
<?php

// Set block ID and classes
$id = $block['anchor'] ?? 'block-' . $block['id'];
$className = 'documents-filter' .
	(!empty($block['className']) ? ' ' . $block['className'] : '') .
	(!empty($block['align']) ? ' align' . $block['align'] : '');

// Get selected document categories (term IDs) from block's ACF field
$allowed_terms = get_field('document_categories') ?: [];
$allowed_term_ids = array_map('intval', $allowed_terms);

// Get terms from document-type taxonomy
$terms = get_terms([
	'taxonomy' => 'document-type',
	'hide_empty' => true,
	'include' => $allowed_term_ids,
]);
$terms_data = array_map(fn($term) => [
	'slug' => $term->slug,
	'name' => html_entity_decode($term->name),
], $terms);

global $wpdb;

$allowed_term_ids = array_map('intval', $allowed_terms);

if (!empty($allowed_term_ids)) {
	$term_ids_sql = implode(',', $allowed_term_ids);

	$years = $wpdb->get_col("
	SELECT DISTINCT YEAR(p.post_date) 
	FROM {$wpdb->posts} p
	INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
	INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
	WHERE p.post_type = 'documents'
	  AND p.post_status = 'publish'
	  AND tt.taxonomy = 'document-type'
	  AND tt.term_id IN ($term_ids_sql)
	ORDER BY YEAR(p.post_date) DESC
");

	$years = array_map('strval', $years);
} else {
	$years = [];
}

if (is_admin()) {
	echo '<p><strong>Documents Filter Block</strong> - selected terms will limit which documents are shown.</p>';
	return;
}
?>

<div class="<?= esc_attr($className); ?>"
	x-data="documentsApp({
		 terms: <?= htmlspecialchars(json_encode($terms_data)) ?>,
		 years: <?= htmlspecialchars(json_encode($years)) ?>,
		 allowedTerms: <?= htmlspecialchars(json_encode($allowed_term_ids)) ?>
	 })"
	x-init="init()"
	x-cloak>


	<div class="documents-filter-controls">
		<div class="inner">

			<div class="tabs type">
				Filter by type
				<ul>
					<li :class="{ 'active': filter === '' }" @click="selectFilter('')">All</li>
					<template x-for="term in terms" :key="term.slug">
						<li :class="{ 'active': filter === term.slug }"
							@click="selectFilter(term.slug)"
							x-text="term.name"></li>
					</template>
				</ul>

				<!-- Mobile Select -->
				<select class="tab-select" x-model="filter" @change="selectFilter($event.target.value)">
					<option value="">All</option>
					<template x-for="term in terms" :key="term.slug">
						<option :value="term.slug" x-text="term.name"></option>
					</template>
				</select>

			</div>

			<div class="dropdown year">
				<span>Filter by year</span>
				<button type="button" @click="toggleDropdown('year')" class="dropdown-toggle">
					<span x-text="yearDropdownText"></span>
				</button>
				<ul x-show="yearDropdownOpen" @click.away="yearDropdownOpen = false" x-transition class="dropdown-menu">
					<li @click="selectYear('')" class="dropdown-item">All</li>
					<template x-for="year in years" :key="year">
						<li
							class="dropdown-item"
							x-text="year"
							@click="selectYear(String(year))">
						</li>
					</template>
				</ul>
			</div>

			<!-- <div class="search-bar">
				<input type="text" x-model="searchQuery" placeholder="Search documents" autocomplete="off" />
			</div> -->

			<!-- <button type="button" @click="resetFilter" class="filter-reset">
				<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="32" height="30" fill="none"><path stroke="#92A097" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4.219 8.132c2.424-4.17 6.94-6.976 12.113-6.976 7.732 0 14 6.268 14 14s-6.268 14-14 14-14-6.268-14-14"/><path stroke="#92A097" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M11.082 8.156h-7v-7"/><path fill="#DF687B" d="m10.871 20.496 4.843-4.843-4.78-4.78.832-.831 4.78 4.779 4.736-4.737.854.854-4.737 4.736 4.78 4.78-.833.832-4.78-4.78-4.842 4.844-.854-.854h.001Z"/></svg>
				Reset
			</button> -->
		</div>
	</div>

	<div class="documents-list">
		<template x-for="post in posts" :key="post.id">
			<div class="document-item" x-html="post.content"></div>
		</template>
	</div>

	<div x-show="noResults && !loading">No documents found.</div>

	<template x-if="!allLoaded">
		<div class="wp-block-button">
			<button class="wp-block-button__link"
				@click="loadMore()"
				x-text="loading ? 'Loading...' : 'Load more'"></button>
		</div>
	</template>
</div>

<script>
	function documentsApp(data = {}) {
		return {
			posts: [],
			page: 1,
			offset: 0,
			postsPerPage: 9,
			filter: '',
			year: '',
			searchQuery: '',
			loading: false,
			allLoaded: false,
			noResults: false,
			dropdownOpen: false,
			yearDropdownOpen: false,
			yearDropdownText: 'All',
			terms: data.terms || [],
			years: data.years || [],
			allowedTerms: data.allowedTerms || [],
			debounceTimeout: null,

			get dropdownText() {
				if (!this.filter) return 'Document type';
				const selected = this.terms.find(term => term.slug === this.filter);
				return selected ? selected.name : 'Document type';
			},

			init() {
				this.loadPosts();
				this.$watch('searchQuery', value => {
					clearTimeout(this.debounceTimeout);
					this.debounceTimeout = setTimeout(() => {
						this.page = 1;
						this.allLoaded = false;
						this.loadPosts(true);
					}, 500);
				});
			},

			loadPosts(reset = false) {
				if (this.loading) return;
				this.loading = true;

				let yearToUse = this.year || this.lastUsedYear;
				if (this.year) this.lastUsedYear = this.year;

				let formData = new FormData();
				formData.append('action', 'load_documents');
				formData.append('page', reset ? 1 : this.page);
				formData.append('posts_per_page', this.postsPerPage);
				formData.append('filter', this.filter);
				formData.append('year', yearToUse);
				formData.append('search', this.searchQuery);
				formData.append('allowed_terms', JSON.stringify(this.allowedTerms));

				fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
						method: 'POST',
						credentials: 'same-origin',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						if (reset) {
							this.posts = data.posts;
							this.page = 2;
							this.noResults = (data.posts.length === 0);
						} else {
							this.posts = this.posts.concat(data.posts);
							this.page += 1;
						}
						if (data.posts.length < this.postsPerPage) {
							this.allLoaded = true;
						}
						this.loading = false;

						this.$nextTick(() => {
							if (typeof GLightbox !== 'undefined') {
								GLightbox({
									selector: '.glightbox'
								});
							}
						});
					})
					.catch(error => {
						console.error('Error:', error);
						this.loading = false;
					});
			},

			selectFilter(slug) {
				this.filter = slug;
				this.dropdownOpen = false;
				this.page = 1;
				this.allLoaded = false;
				this.loadPosts(true);
			},

			selectYear(year) {
				this.year = year;
				this.yearDropdownText = year && year !== '' ? year : 'All';
				this.yearDropdownOpen = false;
				this.page = 1;
				this.allLoaded = false;

				if (year === '' || year === null) {
					this.lastUsedYear = '';
				}

				this.loadPosts(true);
			},

			resetFilter() {
				this.filter = '';
				this.year = '';
				this.searchQuery = '';
				this.dropdownOpen = false;
				this.yearDropdownOpen = false;
				this.page = 1;
				this.allLoaded = false;
				this.loadPosts(true);
			},

			toggleDropdown(type) {
				if (type === 'category') {
					this.dropdownOpen = !this.dropdownOpen;
				} else if (type === 'year') {
					this.yearDropdownOpen = !this.yearDropdownOpen;
				}
			},

			loadMore() {
				this.loadPosts();
			}
		}
	}
</script>
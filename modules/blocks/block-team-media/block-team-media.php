<?php
$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$team_category = get_field('team_category') ?: '';

$className = 'team-media'
	. (!empty($block['className']) ? " {$block['className']}" : '')
	. (!empty($block['align']) ? " align{$block['align']}" : '');

if (is_admin()) {
	echo '<p><strong>Team Media</strong> – interactive on front end.</p>';
	return;
}

?>

<div
	class="<?= esc_attr($className) ?>"
	x-data="teamMediaApp({ initialCategory: <?= json_encode($team_category) ?> })"
	x-init="init()"
	x-cloak>
	<?php if (get_field('filter')): ?>
		<div class="team-controls">

			<h3>Our people</h3>

			<!-- Filter by Category Tabs -->
			<div class="tabs category">
				<ul>
					<template x-for="cat in uniqueCategories" :key="cat.id">
						<li
							:class="{ 'active': categoryFilter == cat.id }"
							@click="selectCategory(cat.id)"
							x-text="cat.name"></li>
					</template>
				</ul>
			</div>
		</div>
	<?php endif; ?>

	<div
		class="team-list <?= (get_field('grid_view')) ? 'grid-view' : ''; ?>"
		x-data="{ displayed: teamMembers }">
		<template x-for="(member, i) in displayed" :key="member.id">
			<div class="team__member team__member__outter" x-html="member.html"
				@click="
                 const img = $el.querySelector('img');
                 if (img && img.src) {
                     window.open(img.src, '_blank');
                 } else {
                     alert('No image found to open.');
                 }
             "></div>
		</template>
	</div>


	<div x-show="loading" class="loading">Loading…</div>
	<div x-show="!loading && displayed.length === 0" class="no-results">Sorry, nothing found.</div>

</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('teamMediaApp', (config) => ({
			initialCategory: config.initialCategory || '',
			allMembers: [],
			displayed: [],
			searchQuery: '',
			categoryFilter: '',
			loading: false,
			modalIndex: 0,
			debounceTimer: null,

			init() {
				// Set the initial category filter before fetching
				this.categoryFilter = this.initialCategory;
				this.fetchMembers();
				this.$watch('searchQuery', () => {
					clearTimeout(this.debounceTimer);
					this.debounceTimer = setTimeout(() => this.applyFilters(), 200);
				});
			},

			fetchMembers() {
				this.loading = true;
				const fd = new FormData();
				fd.append('action', 'load_team_media');

				fetch("<?= admin_url('admin-ajax.php') ?>", {
						method: 'POST',
						credentials: 'same-origin',
						body: fd
					})
					.then(r => r.json())
					.then(json => {
						this.allMembers = json.members;
						// Apply filters immediately after loading members
						this.applyFilters();
						this.loading = false;
					})
					.catch(() => {
						this.loading = false;
					});
			},

			applyFilters() {
				let items = [...this.allMembers];
				const q = this.searchQuery.trim().toLowerCase();

				if (q) {
					items = items.filter(i =>
						i.title.toLowerCase().includes(q) ||
						i.html.toLowerCase().includes(q)
					);
				}

				// Apply category filter if set
				if (this.categoryFilter) {
					items = items.filter(i => i.member_category_id == this.categoryFilter);
				}

				this.displayed = items;
			},

			selectCategory(cat) {
				this.categoryFilter = cat;
				this.applyFilters();
			},

			get uniqueCategories() {
				const map = new Map();
				this.allMembers.forEach(m => {
					if (m.member_category_id && m.member_category_name) {
						map.set(m.member_category_id, m.member_category_name);
					}
				});
				return [...map.entries()].map(([id, name]) => ({
					id,
					name
				}));
			}

		}));
	});
</script>
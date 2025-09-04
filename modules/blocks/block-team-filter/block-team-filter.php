<?php
$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$team_category = get_field('team_category') ?: '';

$category_slug = '';

$filter = get_field('filter');

if ($team_category) {
	$term = get_term($team_category, 'team-category');
	if (!is_wp_error($term)) {
		$category_slug = ' team-cat-' . sanitize_title($term->slug);
	}
}

$className = 'team'
	. (!empty($block['className']) ? " {$block['className']}" : '')
	. (!empty($block['align']) ? " align{$block['align']}" : '')
	. $category_slug;

if (is_admin()) {
	echo '<p><strong>Team Members</strong> – interactive on front end.</p>';
	return;
}
?>

<div
	class="<?= esc_attr($className) ?>"
	x-data="teamApp({ category: <?= json_encode($team_category) ?> })"
	x-init="init()"
	x-cloak>
	<?php if ($filter) { ?>
		<div class="team-controls">
			<!--
		<input type="text" class="search-bar" x-model="searchQuery" placeholder="Search team members…" autocomplete="off" />
		-->

			<!-- Filter by team -->
			<div class="type-tabs">
				<button
					class="tab"
					:class="{ 'active': typeFilter === '' }"
					@click="selectType('')">
					All
				</button>
				<template x-for="type in uniqueTypes" :key="type">
					<button
						class="tab"
						:class="{ 'active': typeFilter === type }"
						@click="selectType(type)"
						x-text="type"></button>
				</template>
			</div>

			<!-- Filter by Role 
		<div class="dropdown role-dropdown">
			<button type="button" class="dropdown-toggle" @click="toggleRoleDropdown()">
				<span x-text="roleDropdownText"></span>
			</button>
			<ul class="dropdown-menu" x-show="roleDropdownOpen" @click.away="roleDropdownOpen = false">
				<li class="dropdown-item" @click="selectRole('')">All Roles</li>
				<template x-for="role in uniqueRoles" :key="role">
					<li class="dropdown-item" @click="selectRole(role)" x-text="role"></li>
				</template>
			</ul>
		</div>
		-->

			<!-- Sorting
		<div class="dropdown sort-dropdown">
			<button type="button" class="dropdown-toggle" @click="toggleSortDropdown()">
				<span x-text="sortDropdownText"></span>
			</button>
			<ul class="dropdown-menu" x-show="sortDropdownOpen" @click.away="sortDropdownOpen = false">
				<li class="dropdown-item" @click="selectSort('default')">Default</li>
				<li class="dropdown-item" @click="selectSort('first_name')">First Name</li>
				<li class="dropdown-item" @click="selectSort('last_name')">Last Name</li>
			</ul>
		</div>
		-->

			<!-- Reset
		<button type="button" class="btn reset-btn" @click="resetFilters()">
			<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="32" height="30" fill="none"><path stroke="#92A097" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4.219 8.132c2.424-4.17 6.94-6.976 12.113-6.976 7.732 0 14 6.268 14 14s-6.268 14-14 14-14-6.268-14-14"/><path stroke="#92A097" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M11.082 8.156h-7v-7"/><path fill="#DF687B" d="m10.871 20.496 4.843-4.843-4.78-4.78.832-.831 4.78 4.779 4.736-4.737.854.854-4.737 4.736 4.78 4.78-.833.832-4.78-4.78-4.842 4.844-.854-.854h.001Z"/></svg>
			Reset
		</button>
		-->
		</div>
	<?php } ?>

	<div class="team-list">
		<template x-for="(member, i) in displayed" :key="member.id">
			<div class="team__member" x-html="member.html" @click="openModal(i)"></div>
		</template>
	</div>

	<div x-show="loading" class="loading">Loading…</div>
	<div x-show="!loading && displayed.length === 0" class="no-results">Sorry, nothing found.</div>

	<div id="team__modal" class="modal" x-ref="modal" @keydown.escape.window="closeModal()" @keydown.window="handleKeyNavigation">
		<div class="modal-inner">
			<div class="modal-controls">
				<button id="close" class="modal-close" @click="closeModal()">×</button>
				<div class="modal-nav">
					<button class="next" :disabled="modalIndex===displayed.length-1" @click="next()">
						<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none">
							<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m17.07 14.99 6.104 5.984-6.104 6.036" />
							<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m21.879 14.99 6.103 5.983-6.103 6.037" />
							<circle cx="21" cy="21" r="20.5" stroke="#292927" />
						</svg>
					</button>
					<button class="prev" :disabled="modalIndex===0" @click="prev()">
						<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none">
							<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="m25.616 14.987-6.104 5.985 6.104 6.038" />
							<path stroke="#DB3553" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20.806 14.988 14.7 20.973l6.105 6.038" />
							<circle cx="21" cy="21" r="20.5" stroke="#292927" />
						</svg>
					</button>
				</div>
			</div>
			<div class="modal-content">
				<img id="team-modal-img" src="" alt="" />
				<div id="title">
					<h3></h3>
					<p></p>
				</div>
				<div id="bio"></div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('teamApp', () => ({
			category: null,
			allMembers: [],
			displayed: [],
			searchQuery: '',
			sortOption: 'default',
			roleFilter: '',
			typeFilter: '',
			loading: false,
			modalIndex: 0,
			sortDropdownOpen: false,
			roleDropdownOpen: false,
			typeDropdownOpen: false,
			debounceTimer: null,

			init() {
				this.category = <?= json_encode($team_category) ?>;
				this.fetchMembers();
				this.$watch('searchQuery', () => {
					clearTimeout(this.debounceTimer);
					this.debounceTimer = setTimeout(() => this.applyFilters(), 200);
				});
			},

			fetchMembers() {
				this.loading = true;
				const fd = new FormData();
				fd.append('action', 'load_team_members');
				fd.append('category', this.category);

				fetch("<?= admin_url('admin-ajax.php') ?>", {
						method: 'POST',
						credentials: 'same-origin',
						body: fd
					})
					.then(r => r.json())
					.then(json => {
						this.allMembers = json.members;
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

				if (this.roleFilter) {
					items = items.filter(i => i.position === this.roleFilter);
				}

				if (this.typeFilter) {
					items = items.filter(i => i.team_type === this.typeFilter);
				}

				if (this.sortOption === 'first_name') {
					items.sort((a, b) => a.first_name.localeCompare(b.first_name));
				} else if (this.sortOption === 'last_name') {
					items.sort((a, b) => a.last_name.localeCompare(b.last_name));
				}

				this.displayed = items;
			},

			resetFilters() {
				this.searchQuery = '';
				this.roleFilter = '';
				this.typeFilter = '';
				this.selectSort('default');
				this.applyFilters();
			},

			toggleSortDropdown() {
				this.sortDropdownOpen = !this.sortDropdownOpen;
			},
			selectSort(value) {
				this.sortOption = value;
				this.sortDropdownOpen = false;
				this.applyFilters();
			},
			get sortDropdownText() {
				if (this.sortOption === 'first_name') return 'First Name';
				if (this.sortOption === 'last_name') return 'Last Name';
				return 'Sort by';
			},

			toggleRoleDropdown() {
				this.roleDropdownOpen = !this.roleDropdownOpen;
			},
			selectRole(role) {
				this.roleFilter = role;
				this.roleDropdownOpen = false;
				this.applyFilters();
			},
			get roleDropdownText() {
				return this.roleFilter || 'Filter by Role';
			},
			get uniqueRoles() {
				return [...new Set(this.allMembers.map(m => m.position).filter(Boolean))].sort();
			},

			toggleTypeDropdown() {
				this.typeDropdownOpen = !this.typeDropdownOpen;
			},
			selectType(type) {
				this.typeFilter = type;
				this.typeDropdownOpen = false;
				this.applyFilters();
			},
			get typeDropdownText() {
				return this.typeFilter || 'Filter by team';
			},
			get uniqueTypes() {
				return [...new Set(this.allMembers.map(m => m.team_type).filter(Boolean))].sort();
			},

			openModal(i) {
				this.modalIndex = i;
				this.populateModal();
			},
			populateModal() {
				const m = this.displayed[this.modalIndex];
				if (!m) return;

				const tmp = document.createElement('div');
				tmp.innerHTML = m.html;

				document.querySelector('#team__modal #title h3').innerText = tmp.querySelector('.title h3')?.innerText || '';
				document.querySelector('#team__modal #title p').innerText = tmp.querySelector('.title p')?.innerText || '';
				document.querySelector('#team__modal #team-modal-img').src = tmp.querySelector('.profile img')?.src || '';
				document.querySelector('#team__modal #bio').innerHTML = tmp.querySelector('.bio')?.innerHTML || '';

				requestAnimationFrame(() => {
					document.getElementById('team__modal')?.classList.add('active');
					document.documentElement.classList.add('menu-opened');
				});
			},
			handleKeyNavigation(e) {
				if (!this.$refs.modal.classList.contains('active')) return;
				if (e.key === 'ArrowRight') this.next();
				if (e.key === 'ArrowLeft') this.prev();
			},
			closeModal() {
				this.$refs.modal.classList.remove('active');
				document.documentElement.classList.remove('menu-opened');
			},
			prev() {
				if (this.modalIndex > 0) {
					this.modalIndex--;
					this.populateModal();
				}
			},
			next() {
				if (this.modalIndex < this.displayed.length - 1) {
					this.modalIndex++;
					this.populateModal();
				}
			}
		}));
	});
</script>
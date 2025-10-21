<?php
$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$team_category = get_field('team_category') ?: '';
$category_slug = '';

$filter = get_field('filter');
$grid_view = get_field('grid_view');

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
    id="<?= esc_attr($id) ?>"
    class="<?= esc_attr($className) ?>"
    x-data="teamApp({ category: <?= json_encode($team_category) ?>, appId: '<?= $id ?>' })"
    x-init="init()"
    x-cloak>
    <?php if ($filter) { ?>
        <div class="team-controls">
            <div class="tabs type">
                <ul>
                    <li :class="{ 'active': typeFilter === '' }" @click="selectType('')">All</li>
                    <template x-for="type in uniqueTypes" :key="type">
                        <li :class="{ 'active': typeFilter === type }" @click="selectType(type)" x-text="type"></li>
                    </template>
                </ul>
            </div>
        </div>
    <?php } ?>

    <div class="team-list <?= ($grid_view) ? 'grid-view' : ''; ?>">
        <template x-for="(member, i) in displayed" :key="member.id">
            <div class="team__member team__member__outter" x-html="member.html" @click="openModal(i)"></div>
        </template>
    </div>

    <div x-show="loading" class="loading">Loading…</div>
    <div x-show="!loading && displayed.length === 0" class="no-results">Sorry, nothing found.</div>

    <!-- Modal scoped per block -->
    <div :id="`${appId}_modal`"
        class="modal"
        x-ref="modal"
        @keydown.escape.window="closeModal()"
        @keydown.window="handleKeyNavigation"
        @click.self="closeModal()">
        <div class="modal-inner">
            <div class="modal-close-wrapper">
                <button id="close" class="modal-close" @click="closeModal()">×</button>
            </div>
            <div class="modal-content">
                <img :id="`${appId}_img`" src="" alt="" />
                <div class="text">
                    <div id="title">
                        <h3></h3>
                        <p></p>
                    </div>
                    <div id="bio"></div>

                    <div class="modal-controls">
                        <div class="modal-nav">
                            <button class="next" :disabled="modalIndex===displayed.length-1" @click="next()">
                                <span>Next</span>
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.1" d="M20 0.5C30.7696 0.5 39.5 9.23045 39.5 20C39.5 30.7696 30.7696 39.5 20 39.5C9.23045 39.5 0.5 30.7696 0.5 20C0.5 9.23045 9.23045 0.5 20 0.5Z" fill="white" stroke="#010035" />
                                    <path d="M15.332 20.2227H24.6654" stroke="#010035" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M20 15.556L24.6667 20.2227L20 24.8894" stroke="#010035" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </button>
                            <button class="prev" :disabled="modalIndex===0" @click="prev()">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.1" d="M20 0.5C9.23045 0.5 0.5 9.23045 0.5 20C0.5 30.7696 9.23045 39.5 20 39.5C30.7696 39.5 39.5 30.7696 39.5 20C39.5 9.23045 30.7696 0.5 20 0.5Z" fill="white" stroke="#010035" />
                                    <path d="M24.668 20.2227H15.3346" stroke="#010035" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M20 15.556L15.3333 20.2227L20 24.8894" stroke="#010035" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Prev</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('teamApp', ({
            category = null,
            appId = ''
        }) => ({
            category,
            appId,
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
                        applyEqualHeights();
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

                if (this.roleFilter) items = items.filter(i => i.position === this.roleFilter);
                if (this.typeFilter)
                    items = items.filter(i => Array.isArray(i.team_type) && i.team_type.includes(this.typeFilter));

                if (this.sortOption === 'first_name') items.sort((a, b) => a.first_name.localeCompare(b.first_name));
                else if (this.sortOption === 'last_name') items.sort((a, b) => a.last_name.localeCompare(b.last_name));

                this.displayed = items;
            },

            resetFilters() {
                this.searchQuery = '';
                this.roleFilter = '';
                this.typeFilter = '';
                this.selectSort('default');
                this.applyFilters();
            },

            selectSort(value) {
                this.sortOption = value;
                this.applyFilters();
            },
            selectRole(role) {
                this.roleFilter = role;
                this.applyFilters();
            },
            selectType(type) {
                this.typeFilter = type;
                this.applyFilters();
            },

            get uniqueRoles() {
                return [...new Set(this.allMembers.map(m => m.position).filter(Boolean))].sort();
            },
            get uniqueTypes() {
                const all = this.allMembers.flatMap(m => m.team_type || []);
                return [...new Set(all)].sort();
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

                const modal = document.querySelector(`#${this.appId}_modal`);
                modal.querySelector('#title h3').innerText = tmp.querySelector('.title h3')?.innerText || '';
                modal.querySelector('#title p').innerText = tmp.querySelector('.title p')?.innerText || '';
                modal.querySelector(`#${this.appId}_img`).src = tmp.querySelector('.profile img')?.src || '';
                modal.querySelector('#bio').innerHTML = tmp.querySelector('.bio')?.innerHTML || '';

                requestAnimationFrame(() => {
                    modal?.classList.add('active');
                    document.documentElement.classList.add('menu-opened');
                });
            },

            handleKeyNavigation(e) {
                const modal = document.querySelector(`#${this.appId}_modal`);
                if (!modal.classList.contains('active')) return;
                if (e.key === 'ArrowRight') this.next();
                if (e.key === 'ArrowLeft') this.prev();
            },

            closeModal() {
                const modal = document.querySelector(`#${this.appId}_modal`);
                modal.classList.remove('active');
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
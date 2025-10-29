<?php
$id = $block['anchor'] ?? 'team-block-' . $block['id'];
$className = 'team-selected'
    . (!empty($block['className']) ? " {$block['className']}" : '')
    . (!empty($block['align']) ? " align{$block['align']}" : '');

$selected_members = get_field('selected_team_members') ?: [];

if (is_admin()) {
    echo '<p><strong>Team Members</strong> - manual selection block with modal.</p>';
    return;
}

$members_data = [];
foreach ($selected_members as $index => $member) {
    $member_id = is_object($member) ? $member->ID : $member;

    $title = get_the_title($member_id);
    $position = ($index === 0) ? 'Chair' : 'Member';
    $img_url = get_the_post_thumbnail_url($member_id, 'team-member') ?: get_template_directory_uri() . '/assets/images/theme/profile.jpg';

    $html = '<div class="team__member">'
        . '<div class="profile">'
        . '<div class="image"><img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '"></div>'
        . '<div class="title"><h3>' . esc_html($title) . '</h3><p class="meta">'.esc_html($position).'</p></div>'
        . '</div>'
        . '<div class="bio"><div class="bio-content">' . apply_filters('the_content', get_post_field('post_content', $member_id)) . '</div></div>'
        . '</div>';

    $members_data[] = [
        'id' => $member_id,
        'title' => $title,
        'html' => $html,
    ];
}

?>

<div
    id="<?= esc_attr($id) ?>"
    class="<?= esc_attr($className) ?>"
    x-data="<?= 'teamSelectApp(' . htmlspecialchars(json_encode([
                'members' => $members_data,
                'appId' => $id
            ]), ENT_QUOTES, 'UTF-8') . ')' ?>"
    x-cloak>

    <div class="team-list grid-view">
        <template x-for="(member, i) in displayed" :key="member.id">
            <div class="team__member team__member__outter" x-html="member.html" @click="openModal(i)"></div>
        </template>
    </div>

    <div x-show="displayed.length === 0" class="no-results">No team members selected.</div>

    <!-- Modal -->
    <div id="<?= esc_attr($id) ?>_modal" class="modal" x-ref="modal"
        @keydown.escape.window="closeModal()"
        @keydown.window="handleKeyNavigation"
        @click.self="closeModal()">
        <div class="modal-inner">
            <div class="modal-close-wrapper">
                <button id="close" class="modal-close" @click="closeModal()">×</button>
            </div>
            <div class="modal-content">
                <img id="<?= esc_attr($id) ?>_img" src="" alt="" />
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
        Alpine.data('teamSelectApp', ({
            members = [],
            appId = ''
        }) => ({
            allMembers: members,
            displayed: members,
            modalIndex: 0,

            init() {
                // Members already loaded, no AJAX needed
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

                this.$refs.modal.querySelector('#title h3').innerText = tmp.querySelector('.title h3')?.innerText || '';
                this.$refs.modal.querySelector('#title p').innerText = tmp.querySelector('.title p')?.innerText || '';
                this.$refs.modal.querySelector('img').src = tmp.querySelector('.profile img')?.src || '';
                this.$refs.modal.querySelector('#bio').innerHTML = tmp.querySelector('.bio')?.innerHTML || '';

                this.$refs.modal.classList.add('active');
                document.documentElement.classList.add('menu-opened');
            },

            handleKeyNavigation(e) {
                const modal = document.querySelector(`#<?= esc_attr($id) ?>_modal`);
                if (!modal.classList.contains('active')) return;
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
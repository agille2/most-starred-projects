import { createApp } from 'vue';
import ProjectDetailsOverlay from './components/ProjectDetailsOverlay.vue';

function setupProjectDetailsOverlay() {
    const overlayTarget = document.getElementById('project-details-overlay');
    if (!overlayTarget) {
        return;
    }

    const app = createApp(ProjectDetailsOverlay);
    const overlay = app.mount(overlayTarget);

    document.querySelectorAll('a.project-detail-link').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const detailsUrl = link.dataset.detailsUrl;

            if (detailsUrl) {
                overlay.open(detailsUrl);
            }
        });
    });
}

if (document.readyState === 'loading') {
    window.addEventListener('DOMContentLoaded', setupProjectDetailsOverlay);
} else {
    setupProjectDetailsOverlay();
}

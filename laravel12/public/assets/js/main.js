(function () {
    document.addEventListener('contextmenu', function (event) {
        event.preventDefault();
    });

    var navToggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-nav]');

    if (navToggle && nav) {
        navToggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    document.querySelectorAll('.dropdown-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.closest('.has-dropdown');
            if (item) {
                item.classList.toggle('is-open');
            }
        });
    });

    document.querySelectorAll('[data-tab]').forEach(function (button) {
        button.addEventListener('click', function () {
            var tab = button.getAttribute('data-tab');
            document.querySelectorAll('[data-tab]').forEach(function (tabButton) {
                tabButton.classList.toggle('is-active', tabButton === button);
            });
            document.querySelectorAll('[data-program-tab]').forEach(function (card) {
                card.hidden = card.getAttribute('data-program-tab') !== tab;
            });
        });
    });

    var activeTab = document.querySelector('[data-tab].is-active');
    var defaultTab = activeTab ? activeTab.getAttribute('data-tab') : '';
    document.querySelectorAll('[data-program-tab]').forEach(function (card) {
        card.hidden = defaultTab && card.getAttribute('data-program-tab') !== defaultTab;
    });

    document.querySelectorAll('.faq__item button').forEach(function (button) {
        button.addEventListener('click', function () {
            var expanded = button.getAttribute('aria-expanded') === 'true';
            var panel = button.parentElement.querySelector('p');
            button.setAttribute('aria-expanded', String(!expanded));
            if (panel) {
                panel.hidden = expanded;
            }
        });
    });

    var counters = document.querySelectorAll('[data-count]');
    if ('IntersectionObserver' in window && counters.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var el = entry.target;
                var target = Number(el.getAttribute('data-count'));
                var start = 0;
                var duration = 850;
                var startTime = null;

                function tick(timestamp) {
                    if (!startTime) {
                        startTime = timestamp;
                    }
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    el.textContent = String(Math.floor(start + (target - start) * progress));
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = String(target);
                    }
                }

                requestAnimationFrame(tick);
                observer.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach(function (counter) {
            observer.observe(counter);
        });
    } else {
        counters.forEach(function (counter) {
            counter.textContent = counter.getAttribute('data-count');
        });
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    const isIpCheckbox = document.getElementById('is_ip');
    const ethnicGroupInput = document.getElementById('ethnic_group');

    function toggleEthnicGroup() {
        if (isIpCheckbox.checked) {
            ethnicGroupInput.disabled = false;
        } else {
            ethnicGroupInput.disabled = true;
            ethnicGroupInput.value = '';
        }
    }

    toggleEthnicGroup();

    isIpCheckbox.addEventListener('change', toggleEthnicGroup);
});

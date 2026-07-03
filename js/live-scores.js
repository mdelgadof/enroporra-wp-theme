(function () {
    'use strict';

    var cards = document.querySelectorAll('.score.live[data-fixture-id]');
    if (!cards.length) return;

    function getEmoji(s1, s2, g1, g2, isDead, finished) {
        var base = enroporraVars.emojiBase;
        if (finished) {
            if (g1 === s1 && g2 === s2) return base + 'zany.png';
            var bw = s1 > s2 ? 1 : s1 < s2 ? 2 : 0;
            var aw = g1 > g2 ? 1 : g1 < g2 ? 2 : 0;
            return bw === aw ? base + 'relieved.png' : base + 'symbols.png';
        }
        if (isDead) return base + 'symbols.png';
        if (g1 === s1 && g2 === s2) return base + 'zany.png';
        var d1 = s1 - g1, d2 = s2 - g2;
        if (d1 >= 0 && d2 >= 0) {
            var t = d1 + d2;
            if (t === 1) return base + 'peeking.png';
            if (t === 2) return base + 'thinking.png';
            return base + 'rolling.png';
        }
        var bw2 = s1 > s2 ? 1 : s1 < s2 ? 2 : 0;
        var lw  = g1 > g2 ? 1 : g1 < g2 ? 2 : 0;
        return bw2 === lw ? base + 'unamused.png' : base + 'tired.png';
    }

    function updateEmojis(card, g1, g2, finished) {
        card.querySelectorAll('.bet-emoji').forEach(function (img) {
            var span = img.previousElementSibling;
            if (!span) return;
            var s1 = parseInt(span.dataset.s1, 10);
            var s2 = parseInt(span.dataset.s2, 10);
            if (isNaN(s1) || isNaN(s2)) return;
            img.src = getEmoji(s1, s2, g1, g2, span.classList.contains('dead-bet'), !!finished);
            img.style.display = 'inline';
        });
    }

    function updateCard(card, d) {
        var g1el = card.querySelector('.score-goals1 span');
        var g2el = card.querySelector('.score-goals2 span');
        var title = card.querySelector('.score-title');
        var badge = card.querySelector('.live-badge');

        if (g1el && d.goals1 !== null) g1el.textContent = d.goals1;
        if (g2el && d.goals2 !== null) g2el.textContent = d.goals2;

        var g1v = d.goals1 !== null ? d.goals1 : (g1el ? parseInt(g1el.textContent, 10) : NaN);
        var g2v = d.goals2 !== null ? d.goals2 : (g2el ? parseInt(g2el.textContent, 10) : NaN);

        if (d.status === 'finished') {
            if (!isNaN(g1v) && !isNaN(g2v)) updateEmojis(card, g1v, g2v, true);
            if (title) title.textContent = enroporraVars.i18n.finished;
            if (badge) badge.remove();
            card.classList.remove('live');
            card.classList.add('past');
        } else {
            if (!isNaN(g1v) && !isNaN(g2v)) updateEmojis(card, g1v, g2v, false);
            if (title && d.minute) {
                var minuteStr = d.minute === 'HT' ? enroporraVars.i18n.halfTime : d.minute + "'";
                title.textContent = enroporraVars.i18n.live + ', ' + minuteStr;
            }
        }
    }

    // Initial emoji computation from DOM goals (before first poll)
    cards.forEach(function (card) {
        var g1el = card.querySelector('.score-goals1 span');
        var g2el = card.querySelector('.score-goals2 span');
        var g1v = g1el ? parseInt(g1el.textContent, 10) : NaN;
        var g2v = g2el ? parseInt(g2el.textContent, 10) : NaN;
        if (!isNaN(g1v) && !isNaN(g2v)) updateEmojis(card, g1v, g2v, false);
    });

    function poll() {
        var body = new URLSearchParams({ action: 'ep_live_scores' });
        fetch(enroporraVars.ajaxUrl, { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                cards.forEach(function (card) {
                    var id = card.dataset.fixtureId;
                    if (data[id]) updateCard(card, data[id]);
                });
            })
            .catch(function () {
                // silences network errors; next tick will retry
            });
    }

    poll();
    setInterval(poll, 60000);
}());

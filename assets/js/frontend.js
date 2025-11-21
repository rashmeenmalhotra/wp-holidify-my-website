// Frontend animation logic
(function() {
    'use strict';

    function createSnowflake(container) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.style.left = Math.random() * 100 + '%';
        el.style.top = '-40px';
        el.style.width = '8px';
        el.style.height = '8px';
        el.style.borderRadius = '50%';
        el.style.background = '#ffffff';
        el.style.opacity = (Math.random() * 0.5 + 0.5).toString();
        el.style.animation = 'holidify-snow ' + (10 + Math.random() * 5).toFixed(2) + 's linear infinite';

        container.appendChild(el);
    }

    function createLeaf(container, emoji) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.textContent = emoji || '🍂';
        el.style.left = Math.random() * 100 + '%';
        el.style.top = '-50px';
        el.style.fontSize = (18 + Math.random() * 12) + 'px';
        el.style.animation = 'holidify-leaf ' + (10 + Math.random() * 8).toFixed(2) + 's linear infinite';

        container.appendChild(el);
    }

    function createHeart(container) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.textContent = '💕';
        el.style.left = Math.random() * 100 + '%';
        el.style.bottom = '-40px';
        el.style.fontSize = (18 + Math.random() * 10) + 'px';
        el.style.animation = 'holidify-heart 8s ease-in infinite';

        container.appendChild(el);
    }

    function createBat(container) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.textContent = '🦇';
        el.style.top = (10 + Math.random() * 60) + '%';
        el.style.left = '-40px';
        el.style.fontSize = (20 + Math.random() * 8) + 'px';
        el.style.animation = 'holidify-bat ' + (12 + Math.random() * 5).toFixed(2) + 's linear infinite';

        container.appendChild(el);
    }

    function createConfetti(container) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.style.left = Math.random() * 100 + '%';
        el.style.top = '-40px';
        el.style.width = '6px';
        el.style.height = '12px';
        var colors = ['#ff6b6b', '#feca57', '#1dd1a1', '#54a0ff', '#5f27cd'];
        el.style.background = colors[Math.floor(Math.random() * colors.length)];
        el.style.animation = 'holidify-confetti 6s ease-in infinite';

        container.appendChild(el);
    }

    function createFirework(container) {
        var base = document.createElement('div');
        base.className = 'holidify-particle';
        base.style.left = Math.random() * 100 + '%';
        base.style.top = (20 + Math.random() * 40) + '%';
        var colors = ['#ff4757', '#ffa502', '#2ed573', '#1e90ff', '#3742fa'];

        // create small burst pieces
        for (var i = 0; i < 10; i++) {
            var dot = document.createElement('div');
            dot.className = 'holidify-particle';
            dot.style.position = 'absolute';
            dot.style.width = '4px';
            dot.style.height = '4px';
            dot.style.borderRadius = '50%';
            dot.style.background = colors[Math.floor(Math.random() * colors.length)];
            var angle = (Math.PI * 2 * i) / 10;
            var dist = 100 + Math.random() * 40;
            dot.style.transform = 'translate(0, 0)';
            dot.style.animation = 'holidify-firework 1.6s ease-out forwards';
            dot.style.setProperty('--tx', (Math.cos(angle) * dist) + 'px');
            dot.style.setProperty('--ty', (Math.sin(angle) * dist) + 'px';
            base.appendChild(dot);
        }

        container.appendChild(base);
        setTimeout(function() {
            if (base.parentNode) {
                base.parentNode.removeChild(base);
            }
        }, 1800);
    }

    function createEgg(container) {
        var el = document.createElement('div');
        el.className = 'holidify-particle';
        el.textContent = '🥚';
        el.style.left = Math.random() * 100 + '%';
        el.style.bottom = '5%';
        el.style.fontSize = (18 + Math.random() * 8) + 'px';
        el.style.animation = 'holidify-egg 3s ease-in-out infinite';

        container.appendChild(el);
    }

    function init() {
        if (typeof holidifyData === 'undefined') {
            return;
        }

        var animType = holidifyData.animation || 'snowflakes';
        var container = document.getElementById('holidifyAnimation');
        if (!container) return;

        var count = 25;

        if (animType === 'snowflakes') {
            for (var i = 0; i < count; i++) {
                createSnowflake(container);
            }
        } else if (animType === 'leaves' || animType === 'maple') {
            var emoji = animType === 'maple' ? '🍁' : '🍂';
            for (var j = 0; j < count; j++) {
                createLeaf(container, emoji);
            }
        } else if (animType === 'hearts') {
            for (var k = 0; k < count; k++) {
                createHeart(container);
            }
        } else if (animType === 'bats') {
            for (var b = 0; b < Math.floor(count / 2); b++) {
                createBat(container);
            }
        } else if (animType === 'confetti') {
            for (var c = 0; c < count; c++) {
                createConfetti(container);
            }
        } else if (animType === 'fireworks') {
            // firework bursts periodically
            setInterval(function() {
                createFirework(container);
            }, 1200);
        } else if (animType === 'eggs') {
            for (var e = 0; e < Math.floor(count / 2); e++) {
                createEgg(container);
            }
        }
    }

    // Add extra keyframes dynamically for some animations
    function injectKeyframes() {
        var style = document.createElement('style');
        style.textContent = ''
            + '@keyframes holidify-snow {'
            + '0%{transform:translateY(-40px);}100%{transform:translateY(110vh);}'
            + '}'
            + '@keyframes holidify-leaf {'
            + '0%{transform:translate3d(0,-40px,0) rotate(0deg);}100%{transform:translate3d(-60px,110vh,0) rotate(360deg);}'
            + '}'
            + '@keyframes holidify-heart {'
            + '0%{bottom:-40px;opacity:0;}20%{opacity:1;}80%{opacity:1;}100%{bottom:110vh;opacity:0;}'
            + '}'
            + '@keyframes holidify-bat {'
            + '0%{left:-40px;}100%{left:110vw;}'
            + '}'
            + '@keyframes holidify-confetti {'
            + '0%{transform:translateY(-40px) rotate(0deg);}100%{transform:translateY(110vh) rotate(720deg);}'
            + '}'
            + '@keyframes holidify-firework {'
            + '0%{transform:translate(0,0);opacity:1;}100%{transform:translate(var(--tx),var(--ty));opacity:0;}'
            + '}'
            + '@keyframes holidify-egg {'
            + '0%,100%{transform:translateY(0);}50%{transform:translateY(-28px);}'
            + '}';
        document.head.appendChild(style);
    }

    document.addEventListener('DOMContentLoaded', function() {
        injectKeyframes();
        init();
    });
})();

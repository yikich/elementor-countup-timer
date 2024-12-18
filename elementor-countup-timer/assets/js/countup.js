class CountUpTimer {
    constructor(container) {
        this.container = container;
        this.startDate = this.parseDateTime(container.dataset.startDate);
        this.daysElement = container.querySelector('[data-count="days"]');
        this.hoursElement = container.querySelector('[data-count="hours"]');
        this.minutesElement = container.querySelector('[data-count="minutes"]');
        this.secondsElement = container.querySelector('[data-count="seconds"]');
        this.timer = null;
        this.init();
    }

    parseDateTime(dateTimeStr) {
        if (!dateTimeStr) return new Date();
        try {
            // 标准化日期时间格式
            dateTimeStr = dateTimeStr.split('+')[0].split('Z')[0].replace(' ', 'T');
            const date = new Date(dateTimeStr);
            return isNaN(date.getTime()) ? new Date() : date;
        } catch (e) {
            console.log('Date parsing error:', e);
            return new Date();
        }
    }

    init() {
        this.stopTimer();
        this.update();
        this.startTimer();
    }

    startTimer() {
        if (!this.timer) {
            this.timer = setInterval(() => this.update(), 1000);
        }
    }

    stopTimer() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }

    update() {
        const now = new Date();
        const diff = Math.floor((now - this.startDate) / 1000);

        const days = Math.max(0, Math.floor(diff / (24 * 60 * 60)));
        const hours = Math.floor((diff % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((diff % (60 * 60)) / 60);
        const seconds = Math.floor(diff % 60);

        this.updateElement(this.daysElement, days);
        this.updateElement(this.hoursElement, hours);
        this.updateElement(this.minutesElement, minutes);
        this.updateElement(this.secondsElement, seconds);
    }

    updateElement(element, value) {
        if (!element) return;
        const currentValue = parseInt(element.textContent);
        if (currentValue !== value) {
            element.classList.remove('animate');
            void element.offsetWidth;
            element.classList.add('animate');
            element.textContent = value.toString().padStart(2, '0');
        }
    }

    destroy() {
        this.stopTimer();
    }

    updateStartDate(newDate) {
        this.startDate = this.parseDateTime(newDate);
        this.update();
    }
}

// 全局存储计时器实例
window.countUpTimers = new Map();

function initOrUpdateTimer(element) {
    const container = element.querySelector('.countup-container');
    if (!container) return;

    const elementId = element.dataset.id || Math.random().toString(36).substr(2, 9);
    
    // 如果实例已存在，更新它
    if (window.countUpTimers.has(elementId)) {
        const timer = window.countUpTimers.get(elementId);
        timer.updateStartDate(container.dataset.startDate);
    } else {
        // 否则创建新实例
        const timer = new CountUpTimer(container);
        window.countUpTimers.set(elementId, timer);
    }
}

if (window.elementorFrontend) {
    // 初始化
    elementorFrontend.hooks.addAction('frontend/element_ready/countup_timer.default', ($element) => {
        initOrUpdateTimer($element[0]);
    });

    // 监听编辑器变化
    elementorFrontend.channels.editor.on('change', (view) => {
        if (view.container && view.container.$el) {
            const element = view.container.$el[0];
            if (element.querySelector('.countup-container')) {
                setTimeout(() => initOrUpdateTimer(element), 100);
            }
        }
    });
}

// 普通页面支持
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.elementor-widget-countup_timer').forEach(widget => {
        initOrUpdateTimer(widget);
    });
});
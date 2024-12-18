<?php
class Elementor_Countup_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'countup_timer';
    }

    public function get_title() {
        return 'Count Up Timer';
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['elementor-countup'];
    }

    public function get_style_depends() {
        return ['elementor-countup'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Timer Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'start_date',
            [
                'label' => 'Start Date',
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i'),
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'digit_color',
            [
                'label' => 'Number Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-value' => 'color: {{VALUE}};',
                ],
                'default' => '#333333',
            ]
        );

        $this->add_control(
            'label_text_color',
            [
                'label' => 'Label Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-label' => 'color: {{VALUE}};',
                ],
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'days_background_color',
            [
                'label' => 'Days Label Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-box:nth-child(1) .countup-label' => 'background-color: {{VALUE}};',
                ],
                'default' => '#F79B27',
            ]
        );

        $this->add_control(
            'hours_background_color',
            [
                'label' => 'Hours Label Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-box:nth-child(2) .countup-label' => 'background-color: {{VALUE}};',
                ],
                'default' => '#F79B27',
            ]
        );

        $this->add_control(
            'minutes_background_color',
            [
                'label' => 'Minutes Label Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-box:nth-child(3) .countup-label' => 'background-color: {{VALUE}};',
                ],
                'default' => '#F79B27',
            ]
        );

        $this->add_control(
            'seconds_background_color',
            [
                'label' => 'Seconds Label Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .countup-box:nth-child(4) .countup-label' => 'background-color: {{VALUE}};',
                ],
                'default' => '#F79B27',
            ]
        );

        $this->end_controls_section();
    }

    protected function _content_template() {
        ?>
        <#
        function getTimeValues(startDateStr) {
            if (!startDateStr) return { days: '00', hours: '00', minutes: '00', seconds: '00' };
            
            var startDate = new Date(startDateStr.replace(' ', 'T'));
            var now = new Date();
            var diff = Math.floor((now - startDate) / 1000);
            diff = Math.max(0, diff);
            
            return {
                days: String(Math.floor(diff / (24 * 60 * 60))).padStart(2, '0'),
                hours: String(Math.floor((diff % (24 * 60 * 60)) / (60 * 60))).padStart(2, '0'),
                minutes: String(Math.floor((diff % (60 * 60)) / 60)).padStart(2, '0'),
                seconds: String(Math.floor(diff % 60)).padStart(2, '0')
            };
        }

        var initialTime = getTimeValues(settings.start_date);

        function updateDisplay() {
            var newTime = getTimeValues(settings.start_date);
            jQuery(view.el).find('[data-count="days"]').text(newTime.days);
            jQuery(view.el).find('[data-count="hours"]').text(newTime.hours);
            jQuery(view.el).find('[data-count="minutes"]').text(newTime.minutes);
            jQuery(view.el).find('[data-count="seconds"]').text(newTime.seconds);
        }

        if (elementorFrontend.isEditMode()) {
            if (window.countUpTimer) {
                clearInterval(window.countUpTimer);
            }
            window.countUpTimer = setInterval(updateDisplay, 1000);
        }
        #>
        <div class="countup-container" data-start-date="{{ settings.start_date }}">
            <div class="countup-box">
                <div class="countup-value" data-count="days">{{ initialTime.days }}</div>
                <div class="countup-label">Days</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="hours">{{ initialTime.hours }}</div>
                <div class="countup-label">Hours</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="minutes">{{ initialTime.minutes }}</div>
                <div class="countup-label">Minutes</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="seconds">{{ initialTime.seconds }}</div>
                <div class="countup-label">Seconds</div>
            </div>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="countup-container" data-start-date="<?php echo esc_attr($settings['start_date']); ?>">
            <div class="countup-box">
                <div class="countup-value" data-count="days">00</div>
                <div class="countup-label">Days</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="hours">00</div>
                <div class="countup-label">Hours</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="minutes">00</div>
                <div class="countup-label">Minutes</div>
            </div>
            <div class="countup-box">
                <div class="countup-value" data-count="seconds">00</div>
                <div class="countup-label">Seconds</div>
            </div>
        </div>
        <?php
    }
}
?>
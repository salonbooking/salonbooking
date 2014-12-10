<?php

class SLN_Form
{
    static public function fieldCurrency($name, $value = null, $settings = array())
    {
        self::fieldSelect($name, SLN_Currency::toArray(), $value, $settings, true);
    }

    static public function fieldTime($name, $value = null, $settings = array())
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('H:i');
        }
        $start = "00:00";

        $curr     = strtotime($start);
        $interval = isset($settings['interval']) ?
            $settings['interval'] :
            SLN_Plugin::getInstance()->getSettings()->getInterval();
        $maxItems = isset($settings['maxItems']) ?
            $settings['maxItems'] : 1440;
        do {
            $items[] = date("H:i", $curr);
            $curr    = strtotime('+' . $interval . ' minutes', $curr);
            $maxItems--;
        } while (date("H:i", $curr) != $start && $maxItems > 0);
        self::fieldSelect($name, $items, $value, $settings);
    }

    static public function fieldDate($name, $value = null, $settings = array())
    {
        if (!($value instanceof \DateTime)) {
            $value = new \Datetime($value);
        }
        echo "<span class=\"sln-date\">";
        self::fieldNumeric($name . '[day]', $value->format('d'), array('min' => 1, 'max' => 31));
        self::fieldSelect($name . '[month]', SLN_Func::getMonths(), $value->format('m'), null, true);
        self::fieldSelect($name . '[year]', SLN_Func::getYears(), $value->format('Y'));
        echo "</span>";
    }

    static public function fieldNumeric($name, $value = null, $settings = array())
    {
        $min      = isset($settings['min']) ? $settings['min'] : 1;
        $max      = isset($settings['max']) ? $settings['max'] : 20;
        $interval = isset($settings['inverval']) ? $settings['interval'] : 1;
        $items    = array();

        for ($i = $min; $i <= $max; $i = $i + $interval) {
            $items[] = $i;
        }
        self::fieldSelect($name, $items, $value, $settings);
    }

    static public function fieldSelect($name, $items, $value, $settings = array(), $map = false)
    {
        if (isset($settings['map'])) {
            $map = $settings['map'];
        }
        ?>
        <select name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>" <?php echo self::attrs($settings) ?>>
            <?php
            foreach ($items as $key => $label) {
                $key      = $map ? $key : $label;
                $selected = $key == $value ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo esc_attr($key) ?>" <?php echo $selected ?>><?php echo $label ?></option>
            <?php
            }
            ?>
        </select>
    <?php
    }

    static public function fieldCheckbox($name, $value = false)
    {
        ?>
        <input type="checkbox" name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>"
               value="1"  <?php echo $value ? 'checked="checked"' : '' ?>/>
    <?php
    }

    static public function fieldText($name, $value = false)
    {
        ?>
        <input type="text" name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>"
               value="<?php echo esc_attr($value) ?>"/>
    <?php
    }

    static private function makeID($val)
    {
        return str_replace('[', '_', str_replace(']', '', $val));
    }

    static private function attrs($settings)
    {
        if (is_array($settings)) {
            return (string)isset($settings['attrs']) ? $settings['attrs'] : '';
        } elseif (is_string($settings)) {
            return $settings;
        }
    }
}

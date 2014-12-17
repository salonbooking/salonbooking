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
        $y     = $value->format('Y');
        $currY = date('Y');
        $m     = $value->format('m');
        $d     = $value->format('d');
        echo "<span class=\"sln-date\">";
        self::fieldNumeric($name . '[day]', $d, array('min' => 1, 'max' => 31));
        self::fieldSelect($name . '[month]', SLN_Func::getMonths(), $m, null, true);
        self::fieldSelect($name . '[year]', SLN_Func::getYears($y < $currY ? $y : $currY - 1), $y);
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

    static public function fieldCheckbox($name, $value = false, $settings = array())
    {
        ?>
        <input type="checkbox" name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>"
               value="1"  <?php echo $value ? 'checked="checked"' : '' ?> <?php echo self::attrs($settings) ?>/>
    <?php
    }

    static public function fieldText($name, $value = false, $settings = array())
    {
        if (!isset($settings['required'])) {
            $settings['required'] = false;
        }
        ?>
        <input type="text" class="form-control" name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>"
               value="<?php echo esc_attr($value) ?>" <?php echo self::attrs($settings) ?>/>
    <?php
    }

    static public function fieldTextarea($name, $value = false, $settings = array())
    {
        if (!isset($settings['required'])) {
            $settings['required'] = false;
        }
        ?>
        <textarea class="form-control" name="<?php echo $name ?>" id="<?php echo self::makeID($name) ?>">
        <?php echo esc_attr($value) ?>" <?php echo self::attrs($settings) ?>
        </textarea>
    <?php
    }

    static public function makeID($val)
    {
        return str_replace('[', '_', str_replace(']', '', $val));
    }

    static private function attrs($settings)
    {
        if (is_array($settings)) {
            $ret = (isset($settings['required']) && $settings['required']) ?
                'required="required" ' : '';
            $ret .= (string)isset($settings['attrs']) ? $settings['attrs'] : '';
            return $ret;
        } elseif (is_string($settings)) {
            return  $settings;
        }

    }
}

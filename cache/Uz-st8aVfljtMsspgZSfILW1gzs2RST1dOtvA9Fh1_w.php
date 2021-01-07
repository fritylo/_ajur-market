<?php
$pug_vars = [];
foreach (array_keys(get_defined_vars()) as $__pug_key) {
    $pug_vars[$__pug_key] = &$$__pug_key;
}
?><?php $pugModule = [
  'Phug\\Formatter\\Format\\BasicFormat::dependencies_storage' => 'pugModule',
  'Phug\\Formatter\\Format\\BasicFormat::helper_prefix' => 'Phug\\Formatter\\Format\\BasicFormat::',
  'Phug\\Formatter\\Format\\BasicFormat::get_helper' => function ($name) use (&$pugModule) {
    $dependenciesStorage = $pugModule['Phug\\Formatter\\Format\\BasicFormat::dependencies_storage'];
    $prefix = $pugModule['Phug\\Formatter\\Format\\BasicFormat::helper_prefix'];
    $format = $pugModule['Phug\\Formatter\\Format\\BasicFormat::dependencies_storage'];

                            if (!isset($$dependenciesStorage)) {
                                return $format->getHelper($name);
                            }

                            $storage = $$dependenciesStorage;

                            if (!isset($storage[$prefix.$name]) &&
                                !(is_array($storage) && array_key_exists($prefix.$name, $storage))
                            ) {
                                throw new \Exception(
                                    var_export($name, true).
                                    ' dependency not found in the namespace: '.
                                    var_export($prefix, true)
                                );
                            }

                            return $storage[$prefix.$name];
                        },
  'Phug\\Formatter\\Format\\BasicFormat::pattern' => function ($pattern) use (&$pugModule) {

                    $args = func_get_args();
                    $function = 'sprintf';
                    if (is_callable($pattern)) {
                        $function = $pattern;
                        $args = array_slice($args, 1);
                    }

                    return call_user_func_array($function, $args);
                },
  'Phug\\Formatter\\Format\\BasicFormat::patterns.html_text_escape' => 'htmlspecialchars',
  'Phug\\Formatter\\Format\\BasicFormat::pattern.html_text_escape' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\BasicFormat::patterns.html_text_escape'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\BasicFormat::available_attribute_assignments' => array (
  0 => 'class',
  1 => 'style',
),
  'Phug\\Formatter\\Format\\BasicFormat::patterns.attribute_pattern' => ' %s="%s"',
  'Phug\\Formatter\\Format\\BasicFormat::pattern.attribute_pattern' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\BasicFormat::patterns.attribute_pattern'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\BasicFormat::patterns.boolean_attribute_pattern' => ' %s="%s"',
  'Phug\\Formatter\\Format\\BasicFormat::pattern.boolean_attribute_pattern' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\BasicFormat::patterns.boolean_attribute_pattern'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\BasicFormat::attribute_assignments' => function (&$attributes, $name, $value) use (&$pugModule) {
    $availableAssignments = $pugModule['Phug\\Formatter\\Format\\BasicFormat::available_attribute_assignments'];
    $getHelper = $pugModule['Phug\\Formatter\\Format\\BasicFormat::get_helper'];

                    if (!in_array($name, $availableAssignments)) {
                        return $value;
                    }

                    $helper = $getHelper($name.'_attribute_assignment');

                    return $helper($attributes, $value);
                },
  'Phug\\Formatter\\Format\\BasicFormat::attribute_assignment' => function (&$attributes, $name, $value) use (&$pugModule) {
    $attributeAssignments = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attribute_assignments'];

                    if (isset($name) && $name !== '') {
                        $result = $attributeAssignments($attributes, $name, $value);
                        if (($result !== null && $result !== false && ($result !== '' || $name !== 'class'))) {
                            $attributes[$name] = $result;
                        }
                    }
                },
  'Phug\\Formatter\\Format\\BasicFormat::merge_attributes' => function () use (&$pugModule) {
    $attributeAssignment = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attribute_assignment'];

                    $attributes = [];
                    foreach (array_filter(func_get_args(), 'is_array') as $input) {
                        foreach ($input as $name => $value) {
                            $attributeAssignment($attributes, $name, $value);
                        }
                    }

                    return $attributes;
                },
  'Phug\\Formatter\\Format\\BasicFormat::array_escape' => function ($name, $input) use (&$pugModule) {
    $arrayEscape = $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape'];
    $escape = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern.html_text_escape'];

                        if (is_array($input) && in_array(strtolower($name), ['class', 'style'])) {
                            $result = [];
                            foreach ($input as $key => $value) {
                                $result[$escape($key)] = $arrayEscape($name, $value);
                            }

                            return $result;
                        }
                        if (is_array($input) || is_object($input) && !method_exists($input, '__toString')) {
                            return $escape(json_encode($input));
                        }
                        if (is_string($input)) {
                            return $escape($input);
                        }

                        return $input;
                    },
  'Phug\\Formatter\\Format\\BasicFormat::attributes_mapping' => array (
),
  'Phug\\Formatter\\Format\\BasicFormat::attributes_assignment' => function () use (&$pugModule) {
    $attrMapping = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_mapping'];
    $mergeAttr = $pugModule['Phug\\Formatter\\Format\\BasicFormat::merge_attributes'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern'];
    $attr = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern.attribute_pattern'];
    $bool = $pugModule['Phug\\Formatter\\Format\\BasicFormat::pattern.boolean_attribute_pattern'];

                        $attributes = call_user_func_array($mergeAttr, func_get_args());
                        $code = '';
                        foreach ($attributes as $originalName => $value) {
                            if ($value !== null && $value !== false && ($value !== '' || $originalName !== 'class')) {
                                $name = isset($attrMapping[$originalName])
                                    ? $attrMapping[$originalName]
                                    : $originalName;
                                if ($value === true) {
                                    $code .= $pattern($bool, $name, $name);

                                    continue;
                                }
                                if (is_array($value) || is_object($value) &&
                                    !method_exists($value, '__toString')) {
                                    $value = json_encode($value);
                                }

                                $code .= $pattern($attr, $name, $value);
                            }
                        }

                        return $code;
                    },
  'Phug\\Formatter\\Format\\BasicFormat::class_attribute_assignment' => function (&$attributes, $value) use (&$pugModule) {

            $split = function ($input) {
                return preg_split('/(?<![\[\{\<\=\%])\s+(?![\]\}\>\=\%])/', strval($input));
            };
            $classes = isset($attributes['class']) ? array_filter($split($attributes['class'])) : [];
            foreach ((array) $value as $key => $input) {
                if (!is_string($input) && is_string($key)) {
                    if (!$input) {
                        continue;
                    }

                    $input = $key;
                }
                foreach ($split($input) as $class) {
                    if (!in_array($class, $classes)) {
                        $classes[] = $class;
                    }
                }
            }

            return implode(' ', $classes);
        },
  'Phug\\Formatter\\Format\\BasicFormat::style_attribute_assignment' => function (&$attributes, $value) use (&$pugModule) {

            if (is_string($value) && mb_substr($value, 0, 7) === '{&quot;') {
                $value = json_decode(htmlspecialchars_decode($value));
            }
            $styles = isset($attributes['style']) ? array_filter(explode(';', $attributes['style'])) : [];
            foreach ((array) $value as $propertyName => $propertyValue) {
                if (!is_int($propertyName)) {
                    $propertyValue = $propertyName.':'.$propertyValue;
                }
                $styles[] = $propertyValue;
            }

            return implode(';', $styles);
        },
]; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['devicer'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(3);
// PUG_DEBUG:3
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(0);
// PUG_DEBUG:0
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'devicer'], ['class' => 'mA'], ['class' => 'mtb1o5'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(1);
// PUG_DEBUG:1
 ?><?php if (method_exists($_pug_temp = (isset($block) ? $block : null), "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?= (is_bool($_pug_temp = $__pug_children(get_defined_vars())) ? var_export($_pug_temp, true) : $_pug_temp) ?><?php } ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(2);
// PUG_DEBUG:2
 ?></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['header'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(14);
// PUG_DEBUG:14
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(5);
// PUG_DEBUG:5
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'header'], ['class' => 'row'], ['class' => 'jcsb'], ['class' => 'p1o25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(6);
// PUG_DEBUG:6
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'logo';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'full']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(13);
// PUG_DEBUG:13
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'aic'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(10);
// PUG_DEBUG:10
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'rel'], ['class' => 'mro75'], ['class' => 'header__cart-wrapper'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(7);
// PUG_DEBUG:7
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['src' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('src', '' . (isset($attach) ? $attach : null) . '/Images/bag.svg')], ['draggable' => 'false'], ['class' => 'header__cart-button'])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(9);
// PUG_DEBUG:9
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['id' => 'cart-items-count'], ['class' => 'abs'], ['class' => 'usn'], ['class' => 'row'], ['class' => 'aic'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(8);
// PUG_DEBUG:8
 ?>1</div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(11);
// PUG_DEBUG:11
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['src' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('src', '' . (isset($attach) ? $attach : null) . '/Images/burger.svg')], ['draggable' => 'false'], ['class' => 'header__burger-button'])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(12);
// PUG_DEBUG:12
 ?></div></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['logo'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'mode', 'normal']];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(38);
// PUG_DEBUG:38
 ?><?php if (method_exists($_pug_temp = (isset($mode) ? $mode : null) == 'normal', "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(37);
// PUG_DEBUG:37
 ?><svg<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(16);
// PUG_DEBUG:16
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'logo'], ['fill' => 'none'], ['version' => '1.1'], ['viewBox' => '0 0 37.335 37.048'], ['xmlns' => 'http://www.w3.org/2000/svg'], ['xmlns:cc' => 'http://creativecommons.org/ns#'], ['xmlns:dc' => 'http://purl.org/dc/elements/1.1/'], ['xmlns:rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(23);
// PUG_DEBUG:23
 ?><metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(22);
// PUG_DEBUG:22
 ?><rdf:rdf><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(21);
// PUG_DEBUG:21
 ?><cc:work<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['rdf:about' => ''])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(18);
// PUG_DEBUG:18
 ?><dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(17);
// PUG_DEBUG:17
 ?>image/svg+xml</dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(19);
// PUG_DEBUG:19
 ?><dc:type<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['rdf:resource' => 'http://purl.org/dc/dcmitype/StillImage'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></dc:type><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(20);
// PUG_DEBUG:20
 ?><dc:title></dc:title></cc:work></rdf:rdf></metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(36);
// PUG_DEBUG:36
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['clip-rule' => 'evenodd'], ['fill-rule' => 'evenodd'], ['id' => 'logo-pattern'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(27);
// PUG_DEBUG:27
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#fad958'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(24);
// PUG_DEBUG:24
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c-2.197 3.009-6.0505 4.5835-9.9102 3.6924-3.8791-0.8956-6.6608-4.0268-7.2983-7.7185 0 0-1.5041-0.8915-2.3645-1.601-0.63752-0.5257-1.5345-1.4567-1.5345-1.4567-0.72173 6.6658 3.6385 12.969 10.334 14.515 6.6944 1.5455 13.375-2.2067 15.65-8.512 0 0-1.215 0.4355-2.0164 0.6328-1.0978 0.2702-2.8601 0.448-2.8601 0.448z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(25);
// PUG_DEBUG:25
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm15.591 4.086c0.4307 0.09945 0.8479 0.22646 1.2502 0.37874 0 0 0.9664-0.59505 1.6259-0.89616 1.105-0.50449 2.97-0.9241 2.97-0.9241-1.4412-1.0704-3.1219-1.8676-4.983-2.2973-7.2271-1.6685-14.439 2.8376-16.107 10.065-1.0002 4.3323 0.21845 8.659 2.912 11.789 0 0 0.16213-1.4581 0.4234-2.3555 0.28755-0.9877 1.0244-2.4268 1.0244-2.4268-0.85006-1.8574-1.116-3.9997-0.62094-6.144 1.1918-5.1622 6.3428-8.3809 11.505-7.1891z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(26);
// PUG_DEBUG:26
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm33.249 18.008c-0.7059 3.0577-2.8008 5.4334-5.4643 6.6148l0.2556 4.0061c4.3363-1.4089 7.8472-4.9916 8.9475-9.7577 1.6686-7.2272-2.8376-14.439-10.065-16.107-2.3067-0.53256-4.6119-0.43607-6.7357 0.17716l2.4253 3.4002c1.1183-0.15182 2.2825-0.10744 3.4472 0.16146 5.1623 1.1918 8.381 6.3428 7.1892 11.505z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(31);
// PUG_DEBUG:31
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#e6a503'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(28);
// PUG_DEBUG:28
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c0.7414-1.0154 1.2942-2.1942 1.5949-3.4968 0.7059-3.0576-0.1355-6.1113-2.0116-8.3406l1.9859-3.4887c3.2798 3.1672 4.8649 7.9264 3.7645 12.692-0.1167 0.5057-0.2606 0.9981-0.4298 1.476 0 0-1.4754 0.7737-2.504 0.9972-0.9179 0.1994-2.3999 0.1604-2.3999 0.1604zm-12.318-18.286c-4.1383 1.5003-7.4559 5.0023-8.5198 9.6106-0.1227 0.5315-0.21202 1.0629-0.2693 1.5921 0 0 1.0053 1.2847 1.8221 1.9169 0.74283 0.575 2.0867 1.1963 2.0867 1.1963-0.2213-1.2377-0.2014-2.5395 0.0994-3.8421 0.7059-3.0576 2.8007-5.4334 5.4642-6.6147z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(29);
// PUG_DEBUG:29
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm11.275 22.78c1.5374 0.3549 3.0738 0.3187 4.505-0.0436l3.0889 2.9782c-2.5667 1.1384-5.5106 1.4845-8.4571 0.8042-2.9002-0.6695-5.3623-2.2317-7.1629-4.3293 0 0 0.17885-1.6924 0.51981-2.7177 0.27813-0.8364 0.93808-2.0529 0.93808-2.0529 1.1965 2.6143 3.5502 4.6643 6.5683 5.3611zm5.5291-18.329c3.9644 1.4822 6.4861 5.4149 6.2022 9.6172l3.3189 3.1291c0.0711-0.2442 0.1357-0.4919 0.1937-0.7429 1.2388-5.3661-0.9263-10.723-5.0819-13.81 0 0-1.5708 0.26125-2.5098 0.63653-0.879 0.35133-2.1231 1.1698-2.1231 1.1698z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(30);
// PUG_DEBUG:30
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm21.744 25.197c3.0276 0.699 6.0512-0.1191 8.2747-1.9565 0 0 0.0465 1.394-0.0778 2.2736-0.152 1.0748-0.7424 2.6784-0.7424 2.6784-2.5336 1.0897-5.4244 1.4113-8.3177 0.7433-3.1162-0.7194-5.7265-2.4693-7.5541-4.8067l4.5242-0.8327c1.1004 0.8956 2.4174 1.5599 3.8931 1.9006zm-6.8636-12.611c1.48-4.1062 5.5756-6.6975 9.9104-6.2886 0 0-0.7462-1.2806-1.3767-1.9804-0.6768-0.75122-1.9768-1.6724-1.9768-1.6724-5.0879 0.9523-9.3824 4.8184-10.621 10.184z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(35);
// PUG_DEBUG:35
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#b87a00'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(32);
// PUG_DEBUG:32
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm18.754 14.268c1.8704 0.4318 3.4856 1.3833 4.7284 2.6668l2.2902-3.1289c-1.6705-1.5657-3.764-2.7247-6.1554-3.2768-2.904-0.6704-5.8054-0.3439-8.3457 0.7554 0 0-0.4695 1.3778-0.628 2.2924-0.1773 1.0231-0.1709 2.6532-0.1709 2.6532 2.224-1.8414 5.2508-2.6618 8.2814-1.9621z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(33);
// PUG_DEBUG:33
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm22.78 15.591c-0.8511 3.6865-3.7212 6.3819-7.1727 7.1876l1.5402 3.5633c4.5302-1.3081 8.2354-4.9673 9.3714-9.8877 0.1231-0.5331 0.2125-1.0662 0.2698-1.597 0 0-0.6535-0.8835-1.1611-1.3654-0.9216-0.8751-2.7482-1.7428-2.7482-1.7428 0.2213 1.2377 0.2013 2.5395-0.0994 3.842z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(34);
// PUG_DEBUG:34
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm20.531 24.832c-4.4411-1.6602-7.0718-6.3955-5.9763-11.14l-3.7389-0.8631c-1.2388 5.366 0.9263 10.723 5.0819 13.81 0 0 1.5708-0.2613 2.5098-0.6365 0.8792-0.3513 2.1235-1.1697 2.1235-1.1697z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g></g></svg><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(63);
// PUG_DEBUG:63
 }  elseif (method_exists($_pug_temp = (isset($mode) ? $mode : null) == 'full', "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(62);
// PUG_DEBUG:62
 ?><svg<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(39);
// PUG_DEBUG:39
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment']($attributes, ['class' => 'logo'], ['fill' => 'none'], ['version' => '1.1'], ['viewBox' => '0 0 122.63 37.048'], ['xmlns' => 'http://www.w3.org/2000/svg'], ['xmlns:cc' => 'http://creativecommons.org/ns#'], ['xmlns:dc' => 'http://purl.org/dc/elements/1.1/'], ['xmlns:rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(46);
// PUG_DEBUG:46
 ?><metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(45);
// PUG_DEBUG:45
 ?><rdf:rdf><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(44);
// PUG_DEBUG:44
 ?><cc:work<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:about' => ''])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(41);
// PUG_DEBUG:41
 ?><dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(40);
// PUG_DEBUG:40
 ?>image/svg+xml</dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(42);
// PUG_DEBUG:42
 ?><dc:type<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:resource' => 'http://purl.org/dc/dcmitype/StillImage'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></dc:type><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(43);
// PUG_DEBUG:43
 ?><dc:title></dc:title></cc:work></rdf:rdf></metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(59);
// PUG_DEBUG:59
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['clip-rule' => 'evenodd'], ['fill-rule' => 'evenodd'], ['id' => 'logo-pattern'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(50);
// PUG_DEBUG:50
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#fad958'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(47);
// PUG_DEBUG:47
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c-2.197 3.009-6.0505 4.5835-9.9102 3.6924-3.8791-0.8956-6.6608-4.0268-7.2983-7.7185 0 0-1.5041-0.8915-2.3645-1.601-0.63752-0.5257-1.5345-1.4567-1.5345-1.4567-0.72173 6.6658 3.6385 12.969 10.334 14.515 6.6944 1.5455 13.375-2.2067 15.65-8.512 0 0-1.215 0.4355-2.0164 0.6328-1.0978 0.2702-2.8601 0.448-2.8601 0.448z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(48);
// PUG_DEBUG:48
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm15.591 4.086c0.4307 0.09945 0.8479 0.22646 1.2502 0.37874 0 0 0.9664-0.59505 1.6259-0.89616 1.105-0.50449 2.97-0.9241 2.97-0.9241-1.4412-1.0704-3.1219-1.8676-4.983-2.2973-7.2271-1.6685-14.439 2.8376-16.107 10.065-1.0002 4.3323 0.21845 8.659 2.912 11.789 0 0 0.16213-1.4581 0.4234-2.3555 0.28755-0.9877 1.0244-2.4268 1.0244-2.4268-0.85006-1.8574-1.116-3.9997-0.62094-6.144 1.1918-5.1622 6.3428-8.3809 11.505-7.1891z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(49);
// PUG_DEBUG:49
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm33.249 18.008c-0.7059 3.0577-2.8008 5.4334-5.4643 6.6148l0.2556 4.0061c4.3363-1.4089 7.8472-4.9916 8.9475-9.7577 1.6686-7.2272-2.8376-14.439-10.065-16.107-2.3067-0.53256-4.6119-0.43607-6.7357 0.17716l2.4253 3.4002c1.1183-0.15182 2.2825-0.10744 3.4472 0.16146 5.1623 1.1918 8.381 6.3428 7.1892 11.505z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(54);
// PUG_DEBUG:54
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#e6a503'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(51);
// PUG_DEBUG:51
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c0.7414-1.0154 1.2942-2.1942 1.5949-3.4968 0.7059-3.0576-0.1355-6.1113-2.0116-8.3406l1.9859-3.4887c3.2798 3.1672 4.8649 7.9264 3.7645 12.692-0.1167 0.5057-0.2606 0.9981-0.4298 1.476 0 0-1.4754 0.7737-2.504 0.9972-0.9179 0.1994-2.3999 0.1604-2.3999 0.1604zm-12.318-18.286c-4.1383 1.5003-7.4559 5.0023-8.5198 9.6106-0.1227 0.5315-0.21202 1.0629-0.2693 1.5921 0 0 1.0053 1.2847 1.8221 1.9169 0.74283 0.575 2.0867 1.1963 2.0867 1.1963-0.2213-1.2377-0.2014-2.5395 0.0994-3.8421 0.7059-3.0576 2.8007-5.4334 5.4642-6.6147z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(52);
// PUG_DEBUG:52
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm11.275 22.78c1.5374 0.3549 3.0738 0.3187 4.505-0.0436l3.0889 2.9782c-2.5667 1.1384-5.5106 1.4845-8.4571 0.8042-2.9002-0.6695-5.3623-2.2317-7.1629-4.3293 0 0 0.17885-1.6924 0.51981-2.7177 0.27813-0.8364 0.93808-2.0529 0.93808-2.0529 1.1965 2.6143 3.5502 4.6643 6.5683 5.3611zm5.5291-18.329c3.9644 1.4822 6.4861 5.4149 6.2022 9.6172l3.3189 3.1291c0.0711-0.2442 0.1357-0.4919 0.1937-0.7429 1.2388-5.3661-0.9263-10.723-5.0819-13.81 0 0-1.5708 0.26125-2.5098 0.63653-0.879 0.35133-2.1231 1.1698-2.1231 1.1698z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(53);
// PUG_DEBUG:53
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm21.744 25.197c3.0276 0.699 6.0512-0.1191 8.2747-1.9565 0 0 0.0465 1.394-0.0778 2.2736-0.152 1.0748-0.7424 2.6784-0.7424 2.6784-2.5336 1.0897-5.4244 1.4113-8.3177 0.7433-3.1162-0.7194-5.7265-2.4693-7.5541-4.8067l4.5242-0.8327c1.1004 0.8956 2.4174 1.5599 3.8931 1.9006zm-6.8636-12.611c1.48-4.1062 5.5756-6.6975 9.9104-6.2886 0 0-0.7462-1.2806-1.3767-1.9804-0.6768-0.75122-1.9768-1.6724-1.9768-1.6724-5.0879 0.9523-9.3824 4.8184-10.621 10.184z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(58);
// PUG_DEBUG:58
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#b87a00'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(55);
// PUG_DEBUG:55
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm18.754 14.268c1.8704 0.4318 3.4856 1.3833 4.7284 2.6668l2.2902-3.1289c-1.6705-1.5657-3.764-2.7247-6.1554-3.2768-2.904-0.6704-5.8054-0.3439-8.3457 0.7554 0 0-0.4695 1.3778-0.628 2.2924-0.1773 1.0231-0.1709 2.6532-0.1709 2.6532 2.224-1.8414 5.2508-2.6618 8.2814-1.9621z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(56);
// PUG_DEBUG:56
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm22.78 15.591c-0.8511 3.6865-3.7212 6.3819-7.1727 7.1876l1.5402 3.5633c4.5302-1.3081 8.2354-4.9673 9.3714-9.8877 0.1231-0.5331 0.2125-1.0662 0.2698-1.597 0 0-0.6535-0.8835-1.1611-1.3654-0.9216-0.8751-2.7482-1.7428-2.7482-1.7428 0.2213 1.2377 0.2013 2.5395-0.0994 3.842z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(57);
// PUG_DEBUG:57
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm20.531 24.832c-4.4411-1.6602-7.0718-6.3955-5.9763-11.14l-3.7389-0.8631c-1.2388 5.366 0.9263 10.723 5.0819 13.81 0 0 1.5708-0.2613 2.5098-0.6365 0.8792-0.3513 2.1235-1.1697 2.1235-1.1697z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(60);
// PUG_DEBUG:60
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm56.376 17.173h-6.3421l-1.0681 2.1527h-3.4277l5.953-11.376h3.4277l5.953 11.376h-3.4277zm-5.241-2.2189h4.1564l-2.0699-4.1895zm25.773 0.1656c0 0.7562-0.1242 1.4075-0.3726 1.954-0.2428 0.5464-0.6016 0.9963-1.0763 1.3495-0.4692 0.3533-1.046 0.6155-1.7304 0.7866-0.6845 0.1656-1.4655 0.2484-2.3431 0.2484-0.414 0-0.839-0.0221-1.2751-0.0663-0.436-0.0386-0.8555-0.0883-1.2585-0.149-0.4029-0.0607-0.7755-0.1242-1.1177-0.1904-0.3367-0.0718-0.6182-0.138-0.8445-0.1987v-2.6661c0.2318 0.0939 0.4857 0.1905 0.7617 0.2898 0.276 0.0939 0.5823 0.1794 0.919 0.2567 0.3422 0.0773 0.7176 0.1408 1.126 0.1904 0.414 0.0497 0.8694 0.0745 1.3662 0.0745 0.5354 0 0.9797-0.0579 1.333-0.1738 0.3587-0.1215 0.6458-0.287 0.861-0.4968 0.2153-0.2153 0.3671-0.4664 0.4554-0.7534 0.0883-0.2871 0.1325-0.6017 0.1325-0.9439v-6.6816h3.0634zm22.85 0.3726c0 0.5685-0.141 1.1039-0.423 1.6062-0.281 0.5023-0.703 0.9411-1.266 1.3164-0.563 0.3754-1.267 0.6735-2.112 0.8942-0.8387 0.2153-1.8212 0.3229-2.9472 0.3229s-2.114-0.1076-2.9641-0.3229c-0.8445-0.2207-1.5483-0.5188-2.1113-0.8942-0.563-0.3753-0.9852-0.8141-1.2667-1.3164s-0.4223-1.0377-0.4223-1.6062v-7.5427h3.0634v6.4415c0 0.3808 0.047 0.7424 0.1408 1.0846 0.0993 0.3367 0.2815 0.632 0.5464 0.8859 0.2705 0.2539 0.6458 0.4554 1.1261 0.6044 0.4857 0.1491 1.1149 0.2236 1.8877 0.2236 0.7672 0 1.391-0.0745 1.8712-0.2236 0.48-0.149 0.853-0.3505 1.118-0.6044 0.27-0.2539 0.452-0.5492 0.546-0.8859 0.094-0.3422 0.141-0.7038 0.141-1.0846v-6.4415h3.072zm12.484 3.8334h-3.072v-11.376h7.7c0.828 0 1.529 0.0856 2.103 0.2567 0.579 0.1711 1.049 0.4139 1.407 0.7286 0.365 0.3146 0.627 0.6954 0.787 1.1425 0.165 0.4416 0.248 0.9356 0.248 1.4821 0 0.4802-0.069 0.8997-0.207 1.2585-0.132 0.3588-0.314 0.6679-0.546 0.9273-0.226 0.2539-0.491 0.4692-0.795 0.6458s-0.624 0.3229-0.96 0.4388l3.725 4.4958h-3.593l-3.444-4.1895h-3.353zm6.068-7.7828c0-0.2208-0.03-0.4084-0.091-0.563-0.055-0.1545-0.154-0.2787-0.298-0.3726-0.143-0.0993-0.336-0.1711-0.579-0.2152-0.238-0.0442-0.536-0.0663-0.894-0.0663h-4.206v2.4342h4.206c0.358 0 0.656-0.0221 0.894-0.0662 0.243-0.0442 0.436-0.1132 0.579-0.207 0.144-0.0994 0.243-0.2263 0.298-0.3809 0.061-0.1545 0.091-0.3422 0.091-0.563z'], ['fill' => '#000'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(61);
// PUG_DEBUG:61
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm51.212 28.375c0.1198 0 0.3902-0.0541 0.3902-0.2126v-4.9997c0-0.17-0.2627-0.2164-0.3941-0.2164-0.1159 0-0.3554 0.0425-0.4057 0.1623l-1.7696 4.3275-1.9357-4.3275c-0.0619-0.1352-0.2744-0.1623-0.4096-0.1623-0.1236 0-0.4521 0.0541-0.4521 0.2164v5.0616c0 0.1236 0.2164 0.1507 0.3053 0.1507 0.0888 0 0.3052-0.0271 0.3052-0.1507v-3.7131l1.6614 3.7015c0.058 0.1275 0.3014 0.1623 0.4251 0.1623 0.1236 0 0.3632-0.031 0.4173-0.1662l1.4682-3.6049v3.5585c0 0.1585 0.2705 0.2126 0.3941 0.2126zm14.751 0c0.1236 0 0.4134-0.0503 0.4134-0.2087 0-0.0154-0.0039-0.0309-0.0116-0.0502l-2.2101-5.0075c-0.0579-0.1275-0.2704-0.1623-0.4018-0.1623-0.1237 0-0.3864 0.0309-0.4405 0.1584l-2.1946 5.0809c-0.0039 0.0116-0.0078 0.0232-0.0078 0.0348 0 0.0966 0.1585 0.1546 0.313 0.1546 0.1275 0 0.255-0.0387 0.2898-0.1198l0.7109-1.6499h2.4072l0.7071 1.6035c0.054 0.1237 0.2704 0.1662 0.425 0.1662zm-1.3253-2.2024h-2.0246l1.0007-2.3221zm15.62 2.2024c0.1043 0 0.2125-0.0232 0.2898-0.0735 0.0695-0.0425 0.1005-0.0927 0.1005-0.1429 0-0.0618-0.0464-0.1198-0.1275-0.1623-0.4251-0.2125-0.6492-0.7303-0.8308-1.136-0.1584-0.3477-0.3284-0.7263-0.6182-0.9929 0.6917-0.1353 1.2983-0.6608 1.2983-1.3949 0-0.8925-0.8655-1.4334-1.704-1.4334h-2.3917c-0.1236 0-0.3902 0.054-0.3902 0.2163v4.9032c0 0.1623 0.2666 0.2164 0.3902 0.2164 0.1276 0 0.3942-0.0541 0.3942-0.2164v-2.2487h1.3098c0.5216 0 0.7766 0.6645 0.9505 1.0548 0.2357 0.5216 0.4791 1.0586 1.0664 1.3523 0.0734 0.0386 0.17 0.058 0.2627 0.058zm-1.5919-2.8979h-1.9975v-2.0053h1.9975c0.5216 0 0.9196 0.5139 0.9196 1.0007 0 0.4869-0.398 1.0046-0.9196 1.0046zm15.535 2.8979c0.0579 0 0.112-0.0078 0.1584-0.0155 0.1043-0.0193 0.3091-0.0966 0.3091-0.2164 0-0.0232-0.0078-0.0502-0.0309-0.0811l-2.1676-2.8901 2.1444-1.9667c0.0309-0.027 0.0463-0.058 0.0463-0.0811 0-0.0773-0.1081-0.1391-0.1893-0.1585-0.0463-0.0115-0.1043-0.0193-0.1661-0.0193-0.1121 0-0.2319 0.0271-0.2975 0.0889l-2.9752 2.724v-2.6158c0-0.1507-0.2781-0.1971-0.3941-0.1971-0.112 0-0.3902 0.0464-0.3902 0.1971v5.0345c0 0.1507 0.2782 0.1971 0.3902 0.1971 0.116 0 0.3941-0.0464 0.3941-0.1971v-1.6923l0.8501-0.7767 1.8855 2.5154c0.0811 0.1082 0.2705 0.1507 0.4328 0.1507zm13.862-0.0928c0.127 0 0.394-0.0541 0.394-0.2164 0-0.1622-0.267-0.2163-0.394-0.2163h-3.107v-1.9744h2.616c0.124 0 0.39-0.0541 0.39-0.2164s-0.266-0.2164-0.39-0.2164h-2.616v-1.9705h3.107c0.127 0 0.394-0.0541 0.394-0.2164s-0.267-0.2163-0.394-0.2163h-3.47c-0.123 0-0.421 0.0695-0.421 0.2318v4.7795c0 0.1623 0.294 0.2318 0.421 0.2318zm12.15 0.0464c0.124 0 0.391-0.0541 0.391-0.2164v-4.6868h1.565c0.123 0 0.39-0.0541 0.39-0.2163 0-0.1623-0.267-0.2164-0.39-0.2164h-3.914c-0.124 0-0.391 0.0541-0.391 0.2164 0 0.1622 0.267 0.2163 0.391 0.2163h1.564v4.6868c0 0.1623 0.267 0.2164 0.394 0.2164z'], ['fill' => '#000'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></svg><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(86);
// PUG_DEBUG:86
 }  elseif (method_exists($_pug_temp = (isset($mode) ? $mode : null) == 'rev', "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(85);
// PUG_DEBUG:85
 ?><svg<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(64);
// PUG_DEBUG:64
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment']($attributes, ['class' => 'logo'], ['width' => '37.335'], ['height' => '37.048'], ['fill' => 'none'], ['version' => '1.1'], ['viewBox' => '0 0 37.335 37.048'], ['xmlns' => 'http://www.w3.org/2000/svg'], ['xmlns:cc' => 'http://creativecommons.org/ns#'], ['xmlns:dc' => 'http://purl.org/dc/elements/1.1/'], ['xmlns:rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(71);
// PUG_DEBUG:71
 ?><metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(70);
// PUG_DEBUG:70
 ?><rdf:rdf><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(69);
// PUG_DEBUG:69
 ?><cc:work<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:about' => ''])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(66);
// PUG_DEBUG:66
 ?><dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(65);
// PUG_DEBUG:65
 ?>image/svg+xml</dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(67);
// PUG_DEBUG:67
 ?><dc:type<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:resource' => 'http://purl.org/dc/dcmitype/StillImage'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></dc:type><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(68);
// PUG_DEBUG:68
 ?><dc:title></dc:title></cc:work></rdf:rdf></metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(84);
// PUG_DEBUG:84
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['clip-rule' => 'evenodd'], ['fill-rule' => 'evenodd'], ['id' => 'logo-pattern'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(75);
// PUG_DEBUG:75
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#b87a00'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(72);
// PUG_DEBUG:72
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c-2.197 3.009-6.0505 4.5835-9.9102 3.6924-3.8791-0.8956-6.6608-4.0268-7.2983-7.7185 0 0-1.5041-0.8915-2.3645-1.601-0.63752-0.5257-1.5345-1.4567-1.5345-1.4567-0.72173 6.6658 3.6385 12.969 10.334 14.515 6.6944 1.5455 13.375-2.2067 15.65-8.512 0 0-1.215 0.4355-2.0164 0.6328-1.0978 0.2702-2.8601 0.448-2.8601 0.448z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(73);
// PUG_DEBUG:73
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm15.591 4.086c0.4307 0.09945 0.8479 0.22646 1.2502 0.37874 0 0 0.9664-0.59505 1.6259-0.89616 1.105-0.50449 2.97-0.9241 2.97-0.9241-1.4412-1.0704-3.1219-1.8676-4.983-2.2973-7.2271-1.6685-14.439 2.8376-16.107 10.065-1.0002 4.3323 0.21845 8.659 2.912 11.789 0 0 0.16213-1.4581 0.4234-2.3555 0.28755-0.9877 1.0244-2.4268 1.0244-2.4268-0.85006-1.8574-1.116-3.9997-0.62094-6.144 1.1918-5.1622 6.3428-8.3809 11.505-7.1891z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(74);
// PUG_DEBUG:74
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm33.249 18.008c-0.7059 3.0577-2.8008 5.4334-5.4643 6.6148l0.2556 4.0061c4.3363-1.4089 7.8472-4.9916 8.9475-9.7577 1.6686-7.2272-2.8376-14.439-10.065-16.107-2.3067-0.53256-4.6119-0.43607-6.7357 0.17716l2.4253 3.4002c1.1183-0.15182 2.2825-0.10744 3.4472 0.16146 5.1623 1.1918 8.381 6.3428 7.1892 11.505z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(79);
// PUG_DEBUG:79
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#e6a503'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(76);
// PUG_DEBUG:76
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c0.7414-1.0154 1.2942-2.1942 1.5949-3.4968 0.7059-3.0576-0.1355-6.1113-2.0116-8.3406l1.9859-3.4887c3.2798 3.1672 4.8649 7.9264 3.7645 12.692-0.1167 0.5057-0.2606 0.9981-0.4298 1.476 0 0-1.4754 0.7737-2.504 0.9972-0.9179 0.1994-2.3999 0.1604-2.3999 0.1604zm-12.318-18.286c-4.1383 1.5003-7.4559 5.0023-8.5198 9.6106-0.1227 0.5315-0.21202 1.0629-0.2693 1.5921 0 0 1.0053 1.2847 1.8221 1.9169 0.74283 0.575 2.0867 1.1963 2.0867 1.1963-0.2213-1.2377-0.2014-2.5395 0.0994-3.8421 0.7059-3.0576 2.8007-5.4334 5.4642-6.6147z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(77);
// PUG_DEBUG:77
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm11.275 22.78c1.5374 0.3549 3.0738 0.3187 4.505-0.0436l3.0889 2.9782c-2.5667 1.1384-5.5106 1.4845-8.4571 0.8042-2.9002-0.6695-5.3623-2.2317-7.1629-4.3293 0 0 0.17885-1.6924 0.51981-2.7177 0.27813-0.8364 0.93808-2.0529 0.93808-2.0529 1.1965 2.6143 3.5502 4.6643 6.5683 5.3611zm5.5291-18.329c3.9644 1.4822 6.4861 5.4149 6.2022 9.6172l3.3189 3.1291c0.0711-0.2442 0.1357-0.4919 0.1937-0.7429 1.2388-5.3661-0.9263-10.723-5.0819-13.81 0 0-1.5708 0.26125-2.5098 0.63653-0.879 0.35133-2.1231 1.1698-2.1231 1.1698z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(78);
// PUG_DEBUG:78
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm21.744 25.197c3.0276 0.699 6.0512-0.1191 8.2747-1.9565 0 0 0.0465 1.394-0.0778 2.2736-0.152 1.0748-0.7424 2.6784-0.7424 2.6784-2.5336 1.0897-5.4244 1.4113-8.3177 0.7433-3.1162-0.7194-5.7265-2.4693-7.5541-4.8067l4.5242-0.8327c1.1004 0.8956 2.4174 1.5599 3.8931 1.9006zm-6.8636-12.611c1.48-4.1062 5.5756-6.6975 9.9104-6.2886 0 0-0.7462-1.2806-1.3767-1.9804-0.6768-0.75122-1.9768-1.6724-1.9768-1.6724-5.0879 0.9523-9.3824 4.8184-10.621 10.184z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(83);
// PUG_DEBUG:83
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#fad958'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(80);
// PUG_DEBUG:80
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm18.754 14.268c1.8704 0.4318 3.4856 1.3833 4.7284 2.6668l2.2902-3.1289c-1.6705-1.5657-3.764-2.7247-6.1554-3.2768-2.904-0.6704-5.8054-0.3439-8.3457 0.7554 0 0-0.4695 1.3778-0.628 2.2924-0.1773 1.0231-0.1709 2.6532-0.1709 2.6532 2.224-1.8414 5.2508-2.6618 8.2814-1.9621z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(81);
// PUG_DEBUG:81
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm22.78 15.591c-0.8511 3.6865-3.7212 6.3819-7.1727 7.1876l1.5402 3.5633c4.5302-1.3081 8.2354-4.9673 9.3714-9.8877 0.1231-0.5331 0.2125-1.0662 0.2698-1.597 0 0-0.6535-0.8835-1.1611-1.3654-0.9216-0.8751-2.7482-1.7428-2.7482-1.7428 0.2213 1.2377 0.2013 2.5395-0.0994 3.842z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(82);
// PUG_DEBUG:82
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm20.531 24.832c-4.4411-1.6602-7.0718-6.3955-5.9763-11.14l-3.7389-0.8631c-1.2388 5.366 0.9263 10.723 5.0819 13.81 0 0 1.5708-0.2613 2.5098-0.6365 0.8792-0.3513 2.1235-1.1697 2.1235-1.1697z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g></g></svg><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(112);
// PUG_DEBUG:112
 }  elseif (method_exists($_pug_temp = (isset($mode) ? $mode : null) == 'rev-full', "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(111);
// PUG_DEBUG:111
 ?><svg<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(87);
// PUG_DEBUG:87
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment']($attributes, ['class' => 'logo'], ['width' => '122.63'], ['height' => '37.048'], ['fill' => 'none'], ['version' => '1.1'], ['viewBox' => '0 0 122.63 37.048'], ['xmlns' => 'http://www.w3.org/2000/svg'], ['xmlns:cc' => 'http://creativecommons.org/ns#'], ['xmlns:dc' => 'http://purl.org/dc/elements/1.1/'], ['xmlns:rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(94);
// PUG_DEBUG:94
 ?><metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(93);
// PUG_DEBUG:93
 ?><rdf:rdf><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(92);
// PUG_DEBUG:92
 ?><cc:work<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:about' => ''])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(89);
// PUG_DEBUG:89
 ?><dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(88);
// PUG_DEBUG:88
 ?>image/svg+xml</dc:format><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(90);
// PUG_DEBUG:90
 ?><dc:type<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['rdf:resource' => 'http://purl.org/dc/dcmitype/StillImage'])
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></dc:type><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(91);
// PUG_DEBUG:91
 ?><dc:title></dc:title></cc:work></rdf:rdf></metadata><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(107);
// PUG_DEBUG:107
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['clip-rule' => 'evenodd'], ['fill-rule' => 'evenodd'], ['id' => 'logo-pattern'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(98);
// PUG_DEBUG:98
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#b87a00'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(95);
// PUG_DEBUG:95
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c-2.197 3.009-6.0505 4.5835-9.9102 3.6924-3.8791-0.8956-6.6608-4.0268-7.2983-7.7185 0 0-1.5041-0.8915-2.3645-1.601-0.63752-0.5257-1.5345-1.4567-1.5345-1.4567-0.72173 6.6658 3.6385 12.969 10.334 14.515 6.6944 1.5455 13.375-2.2067 15.65-8.512 0 0-1.215 0.4355-2.0164 0.6328-1.0978 0.2702-2.8601 0.448-2.8601 0.448z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(96);
// PUG_DEBUG:96
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm15.591 4.086c0.4307 0.09945 0.8479 0.22646 1.2502 0.37874 0 0 0.9664-0.59505 1.6259-0.89616 1.105-0.50449 2.97-0.9241 2.97-0.9241-1.4412-1.0704-3.1219-1.8676-4.983-2.2973-7.2271-1.6685-14.439 2.8376-16.107 10.065-1.0002 4.3323 0.21845 8.659 2.912 11.789 0 0 0.16213-1.4581 0.4234-2.3555 0.28755-0.9877 1.0244-2.4268 1.0244-2.4268-0.85006-1.8574-1.116-3.9997-0.62094-6.144 1.1918-5.1622 6.3428-8.3809 11.505-7.1891z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(97);
// PUG_DEBUG:97
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm33.249 18.008c-0.7059 3.0577-2.8008 5.4334-5.4643 6.6148l0.2556 4.0061c4.3363-1.4089 7.8472-4.9916 8.9475-9.7577 1.6686-7.2272-2.8376-14.439-10.065-16.107-2.3067-0.53256-4.6119-0.43607-6.7357 0.17716l2.4253 3.4002c1.1183-0.15182 2.2825-0.10744 3.4472 0.16146 5.1623 1.1918 8.381 6.3428 7.1892 11.505z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(102);
// PUG_DEBUG:102
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#e6a503'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(99);
// PUG_DEBUG:99
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm24.348 29.27c0.7414-1.0154 1.2942-2.1942 1.5949-3.4968 0.7059-3.0576-0.1355-6.1113-2.0116-8.3406l1.9859-3.4887c3.2798 3.1672 4.8649 7.9264 3.7645 12.692-0.1167 0.5057-0.2606 0.9981-0.4298 1.476 0 0-1.4754 0.7737-2.504 0.9972-0.9179 0.1994-2.3999 0.1604-2.3999 0.1604zm-12.318-18.286c-4.1383 1.5003-7.4559 5.0023-8.5198 9.6106-0.1227 0.5315-0.21202 1.0629-0.2693 1.5921 0 0 1.0053 1.2847 1.8221 1.9169 0.74283 0.575 2.0867 1.1963 2.0867 1.1963-0.2213-1.2377-0.2014-2.5395 0.0994-3.8421 0.7059-3.0576 2.8007-5.4334 5.4642-6.6147z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(100);
// PUG_DEBUG:100
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm11.275 22.78c1.5374 0.3549 3.0738 0.3187 4.505-0.0436l3.0889 2.9782c-2.5667 1.1384-5.5106 1.4845-8.4571 0.8042-2.9002-0.6695-5.3623-2.2317-7.1629-4.3293 0 0 0.17885-1.6924 0.51981-2.7177 0.27813-0.8364 0.93808-2.0529 0.93808-2.0529 1.1965 2.6143 3.5502 4.6643 6.5683 5.3611zm5.5291-18.329c3.9644 1.4822 6.4861 5.4149 6.2022 9.6172l3.3189 3.1291c0.0711-0.2442 0.1357-0.4919 0.1937-0.7429 1.2388-5.3661-0.9263-10.723-5.0819-13.81 0 0-1.5708 0.26125-2.5098 0.63653-0.879 0.35133-2.1231 1.1698-2.1231 1.1698z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(101);
// PUG_DEBUG:101
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm21.744 25.197c3.0276 0.699 6.0512-0.1191 8.2747-1.9565 0 0 0.0465 1.394-0.0778 2.2736-0.152 1.0748-0.7424 2.6784-0.7424 2.6784-2.5336 1.0897-5.4244 1.4113-8.3177 0.7433-3.1162-0.7194-5.7265-2.4693-7.5541-4.8067l4.5242-0.8327c1.1004 0.8956 2.4174 1.5599 3.8931 1.9006zm-6.8636-12.611c1.48-4.1062 5.5756-6.6975 9.9104-6.2886 0 0-0.7462-1.2806-1.3767-1.9804-0.6768-0.75122-1.9768-1.6724-1.9768-1.6724-5.0879 0.9523-9.3824 4.8184-10.621 10.184z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(106);
// PUG_DEBUG:106
 ?><g<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['fill' => '#fad958'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(103);
// PUG_DEBUG:103
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm18.754 14.268c1.8704 0.4318 3.4856 1.3833 4.7284 2.6668l2.2902-3.1289c-1.6705-1.5657-3.764-2.7247-6.1554-3.2768-2.904-0.6704-5.8054-0.3439-8.3457 0.7554 0 0-0.4695 1.3778-0.628 2.2924-0.1773 1.0231-0.1709 2.6532-0.1709 2.6532 2.224-1.8414 5.2508-2.6618 8.2814-1.9621z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(104);
// PUG_DEBUG:104
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm22.78 15.591c-0.8511 3.6865-3.7212 6.3819-7.1727 7.1876l1.5402 3.5633c4.5302-1.3081 8.2354-4.9673 9.3714-9.8877 0.1231-0.5331 0.2125-1.0662 0.2698-1.597 0 0-0.6535-0.8835-1.1611-1.3654-0.9216-0.8751-2.7482-1.7428-2.7482-1.7428 0.2213 1.2377 0.2013 2.5395-0.0994 3.842z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(105);
// PUG_DEBUG:105
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm20.531 24.832c-4.4411-1.6602-7.0718-6.3955-5.9763-11.14l-3.7389-0.8631c-1.2388 5.366 0.9263 10.723 5.0819 13.81 0 0 1.5708-0.2613 2.5098-0.6365 0.8792-0.3513 2.1235-1.1697 2.1235-1.1697z'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path></g></g><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(108);
// PUG_DEBUG:108
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm56.376 17.173h-6.3421l-1.0681 2.1527h-3.4277l5.953-11.376h3.4277l5.953 11.376h-3.4277zm-5.241-2.2189h4.1564l-2.0699-4.1895zm25.773 0.1656c0 0.7562-0.1242 1.4075-0.3726 1.954-0.2428 0.5464-0.6016 0.9963-1.0763 1.3495-0.4692 0.3533-1.046 0.6155-1.7304 0.7866-0.6845 0.1656-1.4655 0.2484-2.3431 0.2484-0.414 0-0.839-0.0221-1.2751-0.0663-0.436-0.0386-0.8555-0.0883-1.2585-0.149-0.4029-0.0607-0.7755-0.1242-1.1177-0.1904-0.3367-0.0718-0.6182-0.138-0.8445-0.1987v-2.6661c0.2318 0.0939 0.4857 0.1905 0.7617 0.2898 0.276 0.0939 0.5823 0.1794 0.919 0.2567 0.3422 0.0773 0.7176 0.1408 1.126 0.1904 0.414 0.0497 0.8694 0.0745 1.3662 0.0745 0.5354 0 0.9797-0.0579 1.333-0.1738 0.3587-0.1215 0.6458-0.287 0.861-0.4968 0.2153-0.2153 0.3671-0.4664 0.4554-0.7534 0.0883-0.2871 0.1325-0.6017 0.1325-0.9439v-6.6816h3.0634zm22.85 0.3726c0 0.5685-0.141 1.1039-0.423 1.6062-0.281 0.5023-0.703 0.9411-1.266 1.3164-0.563 0.3754-1.267 0.6735-2.112 0.8942-0.8387 0.2153-1.8212 0.3229-2.9472 0.3229s-2.114-0.1076-2.9641-0.3229c-0.8445-0.2207-1.5483-0.5188-2.1113-0.8942-0.563-0.3753-0.9852-0.8141-1.2667-1.3164s-0.4223-1.0377-0.4223-1.6062v-7.5427h3.0634v6.4415c0 0.3808 0.047 0.7424 0.1408 1.0846 0.0993 0.3367 0.2815 0.632 0.5464 0.8859 0.2705 0.2539 0.6458 0.4554 1.1261 0.6044 0.4857 0.1491 1.1149 0.2236 1.8877 0.2236 0.7672 0 1.391-0.0745 1.8712-0.2236 0.48-0.149 0.853-0.3505 1.118-0.6044 0.27-0.2539 0.452-0.5492 0.546-0.8859 0.094-0.3422 0.141-0.7038 0.141-1.0846v-6.4415h3.072zm12.484 3.8334h-3.072v-11.376h7.7c0.828 0 1.529 0.0856 2.103 0.2567 0.579 0.1711 1.049 0.4139 1.407 0.7286 0.365 0.3146 0.627 0.6954 0.787 1.1425 0.165 0.4416 0.248 0.9356 0.248 1.4821 0 0.4802-0.069 0.8997-0.207 1.2585-0.132 0.3588-0.314 0.6679-0.546 0.9273-0.226 0.2539-0.491 0.4692-0.795 0.6458s-0.624 0.3229-0.96 0.4388l3.725 4.4958h-3.593l-3.444-4.1895h-3.353zm6.068-7.7828c0-0.2208-0.03-0.4084-0.091-0.563-0.055-0.1545-0.154-0.2787-0.298-0.3726-0.143-0.0993-0.336-0.1711-0.579-0.2152-0.238-0.0442-0.536-0.0663-0.894-0.0663h-4.206v2.4342h4.206c0.358 0 0.656-0.0221 0.894-0.0662 0.243-0.0442 0.436-0.1132 0.579-0.207 0.144-0.0994 0.243-0.2263 0.298-0.3809 0.061-0.1545 0.091-0.3422 0.091-0.563z'], ['fill' => '#fff'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></path><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(110);
// PUG_DEBUG:110
 ?><path<?= (is_bool($_pug_temp = $pugModule['Phug\Formatter\Format\BasicFormat::attributes_assignment'](array(  ), ['d' => 'm51.212 28.375c0.1198 0 0.3902-0.0541 0.3902-0.2126v-4.9997c0-0.17-0.2627-0.2164-0.3941-0.2164-0.1159 0-0.3554 0.0425-0.4057 0.1623l-1.7696 4.3275-1.9357-4.3275c-0.0619-0.1352-0.2744-0.1623-0.4096-0.1623-0.1236 0-0.4521 0.0541-0.4521 0.2164v5.0616c0 0.1236 0.2164 0.1507 0.3053 0.1507 0.0888 0 0.3052-0.0271 0.3052-0.1507v-3.7131l1.6614 3.7015c0.058 0.1275 0.3014 0.1623 0.4251 0.1623 0.1236 0 0.3632-0.031 0.4173-0.1662l1.4682-3.6049v3.5585c0 0.1585 0.2705 0.2126 0.3941 0.2126zm14.751 0c0.1236 0 0.4134-0.0503 0.4134-0.2087 0-0.0154-0.0039-0.0309-0.0116-0.0502l-2.2101-5.0075c-0.0579-0.1275-0.2704-0.1623-0.4018-0.1623-0.1237 0-0.3864 0.0309-0.4405 0.1584l-2.1946 5.0809c-0.0039 0.0116-0.0078 0.0232-0.0078 0.0348 0 0.0966 0.1585 0.1546 0.313 0.1546 0.1275 0 0.255-0.0387 0.2898-0.1198l0.7109-1.6499h2.4072l0.7071 1.6035c0.054 0.1237 0.2704 0.1662 0.425 0.1662zm-1.3253-2.2024h-2.0246l1.0007-2.3221zm15.62 2.2024c0.1043 0 0.2125-0.0232 0.2898-0.0735 0.0695-0.0425 0.1005-0.0927 0.1005-0.1429 0-0.0618-0.0464-0.1198-0.1275-0.1623-0.4251-0.2125-0.6492-0.7303-0.8308-1.136-0.1584-0.3477-0.3284-0.7263-0.6182-0.9929 0.6917-0.1353 1.2983-0.6608 1.2983-1.3949 0-0.8925-0.8655-1.4334-1.704-1.4334h-2.3917c-0.1236 0-0.3902 0.054-0.3902 0.2163v4.9032c0 0.1623 0.2666 0.2164 0.3902 0.2164 0.1276 0 0.3942-0.0541 0.3942-0.2164v-2.2487h1.3098c0.5216 0 0.7766 0.6645 0.9505 1.0548 0.2357 0.5216 0.4791 1.0586 1.0664 1.3523 0.0734 0.0386 0.17 0.058 0.2627 0.058zm-1.5919-2.8979h-1.9975v-2.0053h1.9975c0.5216 0 0.9196 0.5139 0.9196 1.0007 0 0.4869-0.398 1.0046-0.9196 1.0046zm15.535 2.8979c0.0579 0 0.112-0.0078 0.1584-0.0155 0.1043-0.0193 0.3091-0.0966 0.3091-0.2164 0-0.0232-0.0078-0.0502-0.0309-0.0811l-2.1676-2.8901 2.1444-1.9667c0.0309-0.027 0.0463-0.058 0.0463-0.0811 0-0.0773-0.1081-0.1391-0.1893-0.1585-0.0463-0.0115-0.1043-0.0193-0.1661-0.0193-0.1121 0-0.2319 0.0271-0.2975 0.0889l-2.9752 2.724v-2.6158c0-0.1507-0.2781-0.1971-0.3941-0.1971-0.112 0-0.3902 0.0464-0.3902 0.1971v5.0345c0 0.1507 0.2782 0.1971 0.3902 0.1971 0.116 0 0.3941-0.0464 0.3941-0.1971v-1.6923l0.8501-0.7767 1.8855 2.5154c0.0811 0.1082 0.2705 0.1507 0.4328 0.1507zm13.862-0.0928c0.127 0 0.394-0.0541 0.394-0.2164 0-0.1622-0.267-0.2163-0.394-0.2163h-3.107v-1.9744h2.616c0.124 0 0.39-0.0541 0.39-0.2164s-0.266-0.2164-0.39-0.2164h-2.616v-1.9705h3.107c0.127 0 0.394-0.0541 0.394-0.2164s-0.267-0.2163-0.394-0.2163h-3.47c-0.123 0-0.421 0.0695-0.421 0.2318v4.7795c0 0.1623 0.294 0.2318 0.421 0.2318zm12.15 0.0464c0.124 0 0.391-0.0541 0.391-0.2164v-4.6868h1.565c0.123 0 0.39-0.0541 0.39-0.2163 0-0.1623-0.267-0.2164-0.39-0.2164h-3.914c-0.124 0-0.391 0.0541-0.391 0.2164 0 0.1622 0.267 0.2163 0.391 0.2163h1.564v4.6868c0 0.1623 0.267 0.2164 0.394 0.2164z'], ['fill' => '#fff'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(109);
// PUG_DEBUG:109
 ?></path></svg><?php } ?><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['button'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'mode', null]];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(118);
// PUG_DEBUG:118
 ?><button<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(114);
// PUG_DEBUG:114
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'button'], ['class' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('class', 'button_mode_' . (isset($mode) ? $mode : null))])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(115);
// PUG_DEBUG:115
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($text) ? $text : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(116);
// PUG_DEBUG:116
 ?><?php if (method_exists($_pug_temp = (isset($block) ? $block : null), "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?= (is_bool($_pug_temp = $__pug_children(get_defined_vars())) ? var_export($_pug_temp, true) : $_pug_temp) ?><?php } ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(117);
// PUG_DEBUG:117
 ?></button><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['search'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(126);
// PUG_DEBUG:126
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(120);
// PUG_DEBUG:120
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'search'], ['class' => 'plr1o25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(125);
// PUG_DEBUG:125
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'search__content'], ['class' => 'row'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(121);
// PUG_DEBUG:121
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'input';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, $pugModule['Phug\\Formatter\\Format\\BasicFormat::merge_attributes'](['class' => 'search__input'], ['class' => 'w100']), [[false, 'Название магазина...']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(124);
// PUG_DEBUG:124
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'button';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, $pugModule['Phug\\Formatter\\Format\\BasicFormat::merge_attributes'](['class' => 'row']), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(122);
// PUG_DEBUG:122
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['src' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('src', '' . (isset($attach) ? $attach : null) . '/Images/search.svg')])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(123);
// PUG_DEBUG:123
 ?><?php
}); ?></div></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['input'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'placeholder', null]];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(129);
// PUG_DEBUG:129
 ?><input<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(128);
// PUG_DEBUG:128
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'input'], ['placeholder' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('placeholder', (isset($placeholder) ? $placeholder : null))])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(130);
// PUG_DEBUG:130
 ?><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['footer'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(149);
// PUG_DEBUG:149
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(132);
// PUG_DEBUG:132
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'footer'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(148);
// PUG_DEBUG:148
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'footer__content'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(139);
// PUG_DEBUG:139
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'jcsb'], ['class' => 'aic'], ['class' => 'mbo5'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(133);
// PUG_DEBUG:133
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'logo';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'rev-full']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(138);
// PUG_DEBUG:138
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'col'], ['class' => 'aife'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(135);
// PUG_DEBUG:135
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => 'tel:+79780000000'], ['class' => 'tdn'], ['class' => 'footer__tel'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(134);
// PUG_DEBUG:134
 ?>+7 (978) 000-00-00</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(137);
// PUG_DEBUG:137
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => 'mailto:email@email.email'], ['class' => 'tdn'], ['class' => 'footer__email'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(136);
// PUG_DEBUG:136
 ?>email@email.email</a></div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(147);
// PUG_DEBUG:147
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'wrap'], ['class' => 'jcc'], ['class' => 'footer__links'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(141);
// PUG_DEBUG:141
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(140);
// PUG_DEBUG:140
 ?>Данные регистрации юр. лица</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(143);
// PUG_DEBUG:143
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(142);
// PUG_DEBUG:142
 ?>Документы</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(146);
// PUG_DEBUG:146
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(144);
// PUG_DEBUG:144
 ?>Права на деятельность<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(145);
// PUG_DEBUG:145
 ?></a></div></div></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['menu'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(177);
// PUG_DEBUG:177
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(151);
// PUG_DEBUG:151
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'menu'], ['class' => 'abs'], ['class' => 'sh_y_s'], ['class' => 'p1o25'], ['class' => 'jcsb'], ['class' => 'col'], ['class' => 'dn'], ['class' => 'menu_closed'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(160);
// PUG_DEBUG:160
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'col'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(155);
// PUG_DEBUG:155
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'jcsb'], ['class' => 'aic'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(153);
// PUG_DEBUG:153
 ?><span<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'menu__title'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(152);
// PUG_DEBUG:152
 ?>Меню</span><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(154);
// PUG_DEBUG:154
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'menu__close-button'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(159);
// PUG_DEBUG:159
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'col'], ['class' => 'mt1'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(158);
// PUG_DEBUG:158
 ?><?php $__eachScopeVariables = ['mi' => isset($mi) ? $mi : null];foreach (array( 'Главная', 'Магазины', 'Товары', 'О нас', 'Документы' ) as $mi) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(157);
// PUG_DEBUG:157
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'], ['class' => 'menu__item'], ['class' => 'fzo75'], ['class' => 'ff-nn-l'], ['class' => 'mt1'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(156);
// PUG_DEBUG:156
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($mi) ? $mi : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?></a><?php }extract($__eachScopeVariables); ?></div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(176);
// PUG_DEBUG:176
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'menu__footer'], ['class' => 'col'], ['class' => 'jcsb'], ['class' => 'fzo75'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(167);
// PUG_DEBUG:167
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'jcsb'], ['class' => 'aic'], ['class' => 'mbo5'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(161);
// PUG_DEBUG:161
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'logo';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'full']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(166);
// PUG_DEBUG:166
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'col'], ['class' => 'aife'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(163);
// PUG_DEBUG:163
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => 'tel:+79780000000'], ['class' => 'tdn'], ['class' => 'menu__tel'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(162);
// PUG_DEBUG:162
 ?>+7 (978) 000-00-00</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(165);
// PUG_DEBUG:165
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => 'mailto:email@email.email'], ['class' => 'tdn'], ['class' => 'menu__email'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(164);
// PUG_DEBUG:164
 ?>email@email.email</a></div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(175);
// PUG_DEBUG:175
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'wrap'], ['class' => 'jcc'], ['class' => 'menu__links'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(169);
// PUG_DEBUG:169
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(168);
// PUG_DEBUG:168
 ?>Данные регистрации юр. лица</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(171);
// PUG_DEBUG:171
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(170);
// PUG_DEBUG:170
 ?>Документы</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(174);
// PUG_DEBUG:174
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => '#'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(172);
// PUG_DEBUG:172
 ?>Права на деятельность<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(173);
// PUG_DEBUG:173
 ?></a></div></div></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['card-product'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'title', null], [false, 'imageSrc', null], [false, 'price', null], [false, 'href', '#']];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(191);
// PUG_DEBUG:191
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(180);
// PUG_DEBUG:180
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'card-product'], ['class' => 'row'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(181);
// PUG_DEBUG:181
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-product__image'], ['class' => 'rel'], ['style' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('style', 'background-image: url(\'' . (isset($attach) ? $attach : null) . '/Images/Products/' . (isset($imageSrc) ? $imageSrc : null) . '\')')])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(190);
// PUG_DEBUG:190
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-product__main-part'], ['class' => 'col'], ['class' => 'pto5'], ['class' => 'pbo25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(185);
// PUG_DEBUG:185
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-product__title'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(183);
// PUG_DEBUG:183
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-product__price'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(182);
// PUG_DEBUG:182
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($price) ? $price : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(184);
// PUG_DEBUG:184
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($title) ? $title : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(187);
// PUG_DEBUG:187
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-product__content'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(186);
// PUG_DEBUG:186
 ?><?php if (method_exists($_pug_temp = (isset($block) ? $block : null), "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?= (is_bool($_pug_temp = $__pug_children(get_defined_vars())) ? var_export($_pug_temp, true) : $_pug_temp) ?><?php } ?></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(189);
// PUG_DEBUG:189
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['href' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('href', (isset($href) ? $href : null))], ['class' => 'card-product__more-button'], ['class' => 'tdn'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(188);
// PUG_DEBUG:188
 ?>К товару</a></div></div><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['card-shop'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'title', null], [false, 'imgSrc', null], [false, 'href', '#']];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(198);
// PUG_DEBUG:198
 ?><a<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(193);
// PUG_DEBUG:193
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['href' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('href', (isset($href) ? $href : null))], ['class' => 'card-shop'], ['class' => 'col'], ['class' => 'mro5'], ['class' => 'mbo5'], ['class' => 'aic'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(195);
// PUG_DEBUG:195
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-shop__image'], ['class' => 'p1'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(194);
// PUG_DEBUG:194
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['style' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('style', 'background-image: url(\'' . (isset($attach) ? $attach : null) . '/Images/Shops/' . (isset($imgSrc) ? $imgSrc : null) . '\');')], ['class' => 'w100'], ['class' => 'h100'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></div></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(197);
// PUG_DEBUG:197
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'card-shop__title'], ['class' => 'plr1o5'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(196);
// PUG_DEBUG:196
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($title) ? $title : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?></div></a><?php
}; ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixins['how-work-block'] = function ($block, $attributes, $__pug_arguments, $__pug_mixin_vars, $__pug_children) use (&$__pug_mixins, &$pugModule) {
    $__pug_values = [];
    foreach ($__pug_arguments as $__pug_argument) {
        if ($__pug_argument[0]) {
            foreach ($__pug_argument[1] as $__pug_value) {
                $__pug_values[] = $__pug_value;
            }
            continue;
        }
        $__pug_values[] = $__pug_argument[1];
    }
    $__pug_attributes = [[false, 'title', null], [false, 'imageSrc', null]];
    $__pug_names = [];
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        ${$__pug_name} = null;
    }
    foreach ($__pug_attributes as $__pug_argument) {
        $__pug_name = ltrim($__pug_argument[1], "$");
        $__pug_names[] = $__pug_name;
        if ($__pug_argument[0]) {
            ${$__pug_name} = $__pug_values;
            break;
        }
        ${$__pug_name} = array_shift($__pug_values);
        if (is_null(${$__pug_name}) && isset($__pug_argument[2])) {
            ${$__pug_name} = $__pug_argument[2];
        }
    }
    foreach ($__pug_mixin_vars as $__pug_key => &$__pug_value) {
        if (!in_array($__pug_key, $__pug_names)) {
            $$__pug_key = &$__pug_value;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(206);
// PUG_DEBUG:206
 ?><div<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(200);
// PUG_DEBUG:200
 ?><?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment']($attributes, ['class' => 'how-work-block'], ['class' => 'tac'], ['class' => 'mb2'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(201);
// PUG_DEBUG:201
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'how-work-block__image'], ['class' => 'w100'], ['style' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('style', 'background-image: url(\'' . (isset($attach) ? $attach : null) . '/Images/HowWeWork/' . (isset($imageSrc) ? $imageSrc : null) . '\')')])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(203);
// PUG_DEBUG:203
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'how-work-block__title'], ['class' => 'mto75'], ['class' => 'mbo25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(202);
// PUG_DEBUG:202
 ?><?= htmlspecialchars((is_bool($_pug_temp = (isset($title) ? $title : null)) ? var_export($_pug_temp, true) : $_pug_temp)) ?></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(205);
// PUG_DEBUG:205
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'how-work-block__content'], ['class' => 'plr1o25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(204);
// PUG_DEBUG:204
 ?><?php if (method_exists($_pug_temp = (isset($block) ? $block : null), "__toBoolean")
        ? $_pug_temp->__toBoolean()
        : $_pug_temp) { ?><?= (is_bool($_pug_temp = $__pug_children(get_defined_vars())) ? var_export($_pug_temp, true) : $_pug_temp) ?><?php } ?></div></div><?php
}; ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(262);
// PUG_DEBUG:262
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(179);
// PUG_DEBUG:179
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(4);
// PUG_DEBUG:4
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(15);
// PUG_DEBUG:15
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(113);
// PUG_DEBUG:113
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(119);
// PUG_DEBUG:119
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(127);
// PUG_DEBUG:127
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(131);
// PUG_DEBUG:131
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(150);
// PUG_DEBUG:150
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(178);
// PUG_DEBUG:178
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(192);
// PUG_DEBUG:192
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(199);
// PUG_DEBUG:199
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(207);
// PUG_DEBUG:207
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(208);
// PUG_DEBUG:208
 ?><!DOCTYPE html><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(261);
// PUG_DEBUG:261
 ?><html<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['lang' => 'en'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(215);
// PUG_DEBUG:215
 ?><head><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(209);
// PUG_DEBUG:209
 ?><meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['charset' => 'UTF-8'])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(210);
// PUG_DEBUG:210
 ?><meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['name' => 'viewport'], ['content' => 'width=device-width, initial-scale=1.0'])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(211);
// PUG_DEBUG:211
 ?><meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['http-equiv' => 'X-UA-Compatible'], ['content' => 'ie=edge'])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(213);
// PUG_DEBUG:213
 ?><title><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(212);
// PUG_DEBUG:212
 ?>Title</title><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(214);
// PUG_DEBUG:214
 ?><link<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['rel' => 'stylesheet'], ['href' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('href', '' . (isset($link) ? $link : null) . '.css?ver=' . (isset($ver) ? $ver : null))])) ? var_export($_pug_temp, true) : $_pug_temp) ?> /></head><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(259);
// PUG_DEBUG:259
 ?><body><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(258);
// PUG_DEBUG:258
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'devicer';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, $pugModule['Phug\\Formatter\\Format\\BasicFormat::merge_attributes'](['class' => 'rel']), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(216);
// PUG_DEBUG:216
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'menu';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(217);
// PUG_DEBUG:217
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'header';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(218);
// PUG_DEBUG:218
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'search';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(229);
// PUG_DEBUG:229
 ?><section><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(225);
// PUG_DEBUG:225
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'wrap'], ['class' => 'p1o25'], ['class' => 'pro75'], ['class' => 'pbo5'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(219);
// PUG_DEBUG:219
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'PUMA'], [false, 'puma.jpg']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(220);
// PUG_DEBUG:220
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'AliExpress'], [false, 'aliexpress.png']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(221);
// PUG_DEBUG:221
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'NIKE'], [false, 'nike.png']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(222);
// PUG_DEBUG:222
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'adidas'], [false, 'adidas.png']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(223);
// PUG_DEBUG:223
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'Tommy Hilfiger'], [false, 'tommy-hilfiger.png']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(224);
// PUG_DEBUG:224
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-shop';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [[false, 'LEVI\'S'], [false, 'levis.png']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?></div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(228);
// PUG_DEBUG:228
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'jcc'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(227);
// PUG_DEBUG:227
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'button';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'closing']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(226);
// PUG_DEBUG:226
 ?>Ещё магазины<?php
}); ?></div></section><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(246);
// PUG_DEBUG:246
 ?><section<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'plr1o25'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(231);
// PUG_DEBUG:231
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'title'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(230);
// PUG_DEBUG:230
 ?>Популярные товары</div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(233);
// PUG_DEBUG:233
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-product';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Nike Air Max 270 Men\'s or Women\'s'], [false, 'nike.jpg'], [false, '120,00 £']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(232);
// PUG_DEBUG:232
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(236);
// PUG_DEBUG:236
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-product';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Adidas Originals FYW 98 EG5196'], [false, 'adidas.webp'], [false, '2 271,00 UAH']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(234);
// PUG_DEBUG:234
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(235);
// PUG_DEBUG:235
 ?>

Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(238);
// PUG_DEBUG:238
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-product';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Levi’s Кофта с капюшоном из трикотажа'], [false, 'levis.webp'], [false, '1 399,00 UAH']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(237);
// PUG_DEBUG:237
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(240);
// PUG_DEBUG:240
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-product';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Durex штука 32 презерватив эталон мужчина'], [false, 'durex.jpg'], [false, '12.92 $']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(239);
// PUG_DEBUG:239
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(242);
// PUG_DEBUG:242
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'card-product';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Темнокожий прохожий'], [false, 'barak.jpg'], [false, '120 000,00 $']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(241);
// PUG_DEBUG:241
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis ipsum dolor<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(245);
// PUG_DEBUG:245
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'row'], ['class' => 'jcc'], ['class' => 'mt1'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(244);
// PUG_DEBUG:244
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'button';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'closing']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(243);
// PUG_DEBUG:243
 ?>Ещё товары<?php
}); ?></div></section><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(256);
// PUG_DEBUG:256
 ?><section><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(248);
// PUG_DEBUG:248
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['class' => 'title'])) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(247);
// PUG_DEBUG:247
 ?>Как мы работаем?</div><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(250);
// PUG_DEBUG:250
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'how-work-block';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Оригинальная продукция'], [false, 'brands.jpg']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(249);
// PUG_DEBUG:249
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(253);
// PUG_DEBUG:253
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'how-work-block';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Быстрая доставка'], [false, 'fast.jpg']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(251);
// PUG_DEBUG:251
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(252);
// PUG_DEBUG:252
 ?>

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.<?php
}); ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(255);
// PUG_DEBUG:255
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'how-work-block';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](true, array(  ), [[false, 'Поможем с вопросами'], [false, 'support.jpg']], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(254);
// PUG_DEBUG:254
 ?>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.<?php
}); ?></section><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(257);
// PUG_DEBUG:257
 ?><?php if (!isset($__pug_mixins)) {
    $__pug_mixins = [];
}
$__pug_mixin_vars = [];
foreach (array_keys(get_defined_vars()) as $__local_pug_key) {
    if (mb_substr($__local_pug_key, 0, 6) === '__pug_' || in_array($__local_pug_key, ['attributes', 'block', 'pug_vars'])) {
        continue;
    }
    $pug_vars[$__local_pug_key] = &$$__local_pug_key;
    $__local_pug_ref = &$GLOBALS[$__local_pug_key];
    $__local_pug_value = &$$__local_pug_key;
    if($__local_pug_ref !== $__local_pug_value){
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
        continue;
    }
    $__local_pug_savedValue = $__local_pug_value;
    $__local_pug_value = ($__local_pug_value === true) ? false : true;
    $__local_pug_isGlobalReference = ($__local_pug_value === $__local_pug_ref);
    $__local_pug_value = $__local_pug_savedValue;
    if (!$__local_pug_isGlobalReference) {
        $__pug_mixin_vars[$__local_pug_key] = &$__local_pug_value;
    }
}
if (!isset($__pug_children)) {
    $__pug_children = null;
}
$__pug_mixin_name = 'footer';
if (!isset($__pug_mixins[$__pug_mixin_name])) {
    throw new \InvalidArgumentException("Unknown $__pug_mixin_name mixin called.");
}

$__pug_mixins[$__pug_mixin_name](false, array(  ), [], $__pug_mixin_vars, function ($__pug_children_vars) use (&$__pug_mixins, $__pug_children, $pug_vars, &$pugModule) {
    foreach (array_keys($__pug_children_vars) as $__local_pug_key) {
        if (mb_substr($__local_pug_key, 0, 6) === '__pug_') {
            continue;
        }
        if(isset($pug_vars[$__local_pug_key])){
            $$__local_pug_key = &$pug_vars[$__local_pug_key];
            continue;
        }
        $__local_pug_ref = &$GLOBALS[$__local_pug_key];
        $__local_pug_value = &$__pug_children_vars[$__local_pug_key];
        if($__local_pug_ref !== $__local_pug_value){
            $$__local_pug_key = &$__local_pug_value;
            continue;
        }
    }
    ?><?php
}); ?><?php
}); ?></body><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(260);
// PUG_DEBUG:260
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\BasicFormat::attributes_assignment'](array(  ), ['src' => $pugModule['Phug\\Formatter\\Format\\BasicFormat::array_escape']('src', '' . (isset($link) ? $link : null) . '.bundle.js')])) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script></html>
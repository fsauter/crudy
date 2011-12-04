<?php
/**
 * Holds the ColorCLI class.
 * 
 * @author Florian Sauter <floonweb@gmail.com>
 * @version 0.1
 * @package crudy
 */

/**
 * PHP Class for Coloring PHP Command Line (CLI) Scripts Output
 * 
 * PHP Command Line Interface (CLI) has not built-in coloring for script output,
 * like example Perl language has (perldoc.perl.org/Term/ANSIColor.html). So I 
 * decided to make own class for adding colors on PHP CLI output. This class works 
 * only Bash shells. This class is easy to use. Just create new instance of class 
 * and call getColoredString function with string and foreground color and/or 
 * background color.
 * 
 * @link http://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
 * @link https://gist.github.com/1315354
 * 
 * @author JR, Jesse Donat, Florian Sauter
 *
 */
class ColorCLI {
	
	static $foreground_colors = array(
		'black'        => '0;30', 'dark_gray'    => '1;30',
		'blue'         => '0;34', 'light_blue'   => '1;34',
		'green'        => '0;32', 'light_green'  => '1;32',
		'cyan'         => '0;36', 'light_cyan'   => '1;36',
		'red'          => '0;31', 'light_red'    => '1;31',
		'purple'       => '0;35', 'light_purple' => '1;35',
		'brown'        => '0;33', 'yellow'	 => '1;33',
		'light_gray'   => '0;37', 'white'        => '1;37',
	);

	static $background_colors = array(
		'black'        => '40', 'red'          => '41',
		'green'        => '42', 'yellow'       => '43',
		'blue'         => '44', 'magenta'      => '45',
		'cyan'         => '46', 'light_gray'   => '47',
	);

	/**
	 * Returns colored string.
	 * 
	 * @param string $string
	 * @param string $foreground_color
	 * @param string $background_color
	 */
	public static function getColoredString($string, $foreground_color = null, $background_color = null) {
		$colored_string = "";

		// Check if given foreground color found
		if ( isset(self::$foreground_colors[$foreground_color]) ) {
			$colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
		}
		// Check if given background color found
		if ( isset(self::$background_colors[$background_color]) ) {
			$colored_string .= "\033[" . self::$background_colors[$background_color] . "m";
		}

		// Add string and end coloring
		$colored_string .=  $string . "\033[0m";

		return $colored_string;
	}
	
	public static function __callStatic($name, $arguments) {
		$foreground_color = null;
		$background_color = null;
		
		if(isset($arguments[0])) {
			$foreground_color = $arguments[0];
		}
		
		if(isset($arguments[1])) {
			$background_color = $arguments[1];
		}
		
		return self::getColoredString($name, $foreground_color, $background_color);
	}

	/**
	 * Returns all foreground color names.
	 * @return string[]
	 */
	public static function getForegroundColors() {
		return array_keys(self::$foreground_colors);
	}

	/**
	 * Returns all background color names.
	 * @return string[]
	 */
	public static function getBackgroundColors() {
		return array_keys(self::$background_colors);
	}
}
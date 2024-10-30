<?php
/**
 * Plugin Name: MSEuroRates
 * Plugin URI: http://www.moonsoft.es
 * Description: euro exchange rates from european central bank
 * Version:  1.0
 * Author: Moonsoft Team
 * Author URI: http://www.moonsoft.es
 * License: GPL2
 */
 
	// Creating the widget 
class mser_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'mser_widget', 

// Widget name will appear in UI
__('MSEuroRates Widget', 'mser_widget_domain'), 

// Widget description
array( 'description' => __( 'Euro Exchange Rates', 'mser_widget_domain' ), ) 
);

if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( $this, 'css' ) );
		}
		
		
}


function css() {
?>

<style type="text/css">

.tableheader{
font-size:12px;
font-weight:bold;
color:#fff;
background-color:#fff;
border:1px solid #aaa;
border-radius:8px;

}

.tableheader td{
font-size:12px;
font-weight:bold;
color:#fff;
background-color:#6B95B5;
border:1px solid #aaa;
border-radius:4px;
text-align:center;

}


.rowrates1{

background-color:#D4DEE6;
border:1px solid #aaa;


}
.currtd{
vertical-align:middle;
}
</style>

<?php
	}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];



if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( $instance['title'] );
			echo $args['after_title'];
		}
		
		
		
//echo content
$textos["USD"]= 	"US dollar";
        $textos["JPY"]= 	"Japanese yen";
        $textos["BGN"]= 	"Bulgarian lev";
        $textos["CZK"]= 	"Czech koruna";
        $textos["DKK"]= 	"Danish krone";
        $textos["GBP"]= 	"Pound sterling";
        $textos["HUF"]= 	"Hungarian forint";
        $textos["LTL"]= 	"Lithuanian litas";
        $textos["LVL"]= 	"Latvian lats";
        $textos["PLN"]= 	"Polish zloty";
        $textos["RON"]= 	"New Romanian leu";
        $textos["SEK"]= 	"Swedish krona";
        $textos["CHF"]= 	"Swiss franc";
        $textos["NOK"]= 	"Norwegian krone";
        $textos["HRK"]= 	"Croatian kuna";
        $textos["RUB"]= 	"Russian rouble";
        $textos["TRY"]= 	"Turkish lira";
        $textos["AUD"]= 	"Australian dollar";
        $textos["BRL"]= 	"Brasilian real";
        $textos["CAD"]= 	"Canadian dollar";
        $textos["CNY"]= 	"Ch.yuan renminbi";
        $textos["HKD"]= 	"Hong Kong dollar";
        $textos["IDR"]= 	"Indonesian rupiah";
        $textos["ILS"]= 	"Israeli shekel";
        $textos["INR"]= 	"Indian rupee";
        $textos["KRW"]= 	"South Korean won";
        $textos["MXN"]= 	"Mexican peso";
        $textos["MYR"]= 	"Malaysian ringgit";
        $textos["NZD"]= 	"N.Zealand dollar";
        $textos["PHP"]= 	"Philippine peso";
        $textos["SGD"]= 	"Singapore dollar";
        $textos["THB"]= 	"Thai baht";
        $textos["ZAR"]= 	"South African rand";



# Read currency exchanges rates


$stuff = file("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml");

# $xld may be used in your output to inform you user or admin
# Extract exchange rates
//$exchrate[EUR] = 1.00;
$timetxt="";
foreach ($stuff as $line) {

    if(preg_match("/time='([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})'/",$line,$gott)){
    
    $timetxt=$gott[1]."-".$gott[2]."-".$gott[3];
    }
    
    preg_match("/currency='([[:alpha:]]+)'/",$line,$gota);
    
    
    if (preg_match("/rate='([[:graph:]]+)'/",$line,$gotb)) {
    
        //if (!preg_match("/$gota[1]/",$params->get('exclude'),$gotz)){
            $exchrate[$gota[1]] = $gotb[1];
        //}
    }
}


echo '<div style="height:200px;overflow-x:hidden">';
echo '<table cellspacing="0" cellpadding="2" width="100%">';
echo '<tr class="tableheader">';
echo '<td nowrap colspan="2">CURR</td>';
echo '<td nowrap>'.$timetxt.'</td>';
echo '<td>RATE</td>';
echo '</tr>';

$evenrow=false;
foreach ($exchrate as $code=>$num){
$evenrow=!$evenrow;
echo "<tr class='rowrates$evenrow'>";

echo "<td nowrap  class='currtd'>";

echo '<img src="' . plugins_url( 'images/'.$code.'.png' , __FILE__ ) . '" /> </td>';
echo "<td><b>".$code."</b></td>";
echo "<td nowrap title='".$textos[$code].":".$num."'>".$textos[$code]."</td>";
echo "<td>".$num."</td>";
echo "</tr>";

}


echo '</table>';

echo '</div>';

















echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Titulo', 'msr_widget_domain' );
}
// Widget admin form
?>

<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class 

// Register and load the widget
function mser_load_widget() {
	register_widget( 'mser_widget' );
}
add_action( 'widgets_init', 'mser_load_widget' );
<?php 

/**
 * RedBean Setup
 * Helper class to quickly setup RedBean for you
 * @package 		RedBean/Setup.php
 * @description		Helper class to quickly setup RedBean for you
 * @author			Gabor de Mooij
 * @license			BSD
 */
class RedBean_Setup {


		/**
		 * @param  string $dsn
		 * @param  string $username
		 * @param  string $password
		 * @return RedBean_ToolBox $toolbox
		 */
        public static function kickstart( $dsn, $username, $password ) {

            $pdo = new Redbean_Driver_PDO( "mysql:host=localhost;dbname=oodb","root","" );
            $adapter = new RedBean_DBAdapter( $pdo );
            $writer = new RedBean_QueryWriter_MySQL( $adapter );
            $redbean = new RedBean_OODB( $writer );

            //add concurrency shield
			$logger = new RedBean_ChangeLogger( $writer );
            $redbean->addEventListener( "open", $logger );
            $redbean->addEventListener( "update", $logger);
			$redbean->addEventListener( "delete", $logger);

            //deliver everything back in a neat toolbox
            return new RedBean_ToolBox( $redbean, $adapter, $writer );

        }

		/**
		 * @param  string $dsn
		 * @param  string $username
		 * @param  string $password
		 * @return RedBean_ToolBox $toolbox
		 */
		public static function kickstartDev( $dsn, $username, $password ) {
			$toolbox = self::kickstart($dsn, $username, $password);
			return $toolbox;
		}

		/**
		 * @param  string $dsn
		 * @param  string $username
		 * @param  string $password
		 * @return RedBean_ToolBox $toolbox
		 */
		public static function kickstartFrozen( $dsn, $username, $password ) {
			$toolbox = self::kickstart($dsn, $username, $password);
			$toolbox->getRedBean()->freeze(true);
			return $toolbox;
		}

		/**
		 * @param  string $dsn
		 * @param  string $username
		 * @param  string $password
		 * @return RedBean_ToolBox $toolbox
		 */
		public static function kickstartDebug( $dsn, $username, $password ) {
			$toolbox = self::kickstart($dsn, $username, $password);
			$toolbox->getDatabaseAdapter()->getDatabase()->setDebug( true );
			return $toolbox;
		}

}

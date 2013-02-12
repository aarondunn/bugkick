<?php
/**
 * ClearTmpCommand
 *
 * @author f0t0n
 */
class ClearTmpCommand extends Command {

	/**
	 * The maximum age in minutes for temporary files. <br />
	 * If the age is bigger the file must to be deleted.
	 */
	const MAX_TMP_FILE_AGE = 30;
	
	public function actionIndex($maxAge = self::MAX_TMP_FILE_AGE) {
		$tmpRoot = realpath(
			Yii::getPathOfAlias('webroot') 
			. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'temp'
		);
		$tmpFiles = array();
		$limit = 64;
		$offset = 0;
		do {
			$criteria = new CDbCriteria();
			$criteria->condition = new CDbExpression(
				'TIMESTAMPDIFF(MINUTE, t.created_at, CURRENT_TIMESTAMP) > :age'
			);
			$criteria->params = array(':age'=>$maxAge);
			$criteria->limit = $limit;
			$criteria->offset = $offset;
			$tmpFiles = TmpFile::model()->findAll($criteria);
			$offset += $limit;
			foreach($tmpFiles as $file) {
				$path = $tmpRoot . $file->path;
				if(is_file($path))
					unlink($path);
				$file->delete();
			}
		} while(count($tmpFiles) > 0);
		
	}
}
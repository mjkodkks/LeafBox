<?php
class MediaController extends Controller{

  /* Initial */

  public function getMedia($bid)
  {
    $book = Book::find($bid);
    $media = null;
    $unsetReturnDate = "0000-00-00 00:00:00";
    switch (Input::get('media_type')) {
      case 'braille':
        $media = $book->braille()->get();
        break;
      case 'cassette':
        $media = $book->cassette()->get();
        break;
      case 'cd':
        $media = $book->cd()->get();
        break;
      case 'daisy':
        $media = $book->daisy()->get();
        break;
      default:
        $media = $book->dvd()->get();
        break;
    }

    if(Input::get('media_type') == 'braille') {
      foreach ($media as $item) {
        $item->examiner = $item->examiner()->first();
        $borrow = $item->borrow()->get()->last();
        $item->submedia_id = $item->getSubmediaRangeID();
        if(isset($borrow) && $borrow->actual_returned == $unsetReturnDate)
          $item->borrower = $borrow->borrower()->first();
      }
    } else {
      foreach ($media as $item) {
        $borrow = $item->borrow()->get()->last();
        $item->submedia_id = $item->getSubmediaRangeID();
        if(isset($borrow) && $borrow->actual_returned == $unsetReturnDate)
          $item->borrower = $borrow->borrower()->first();
      }
    }
    return $media;
  }

  public function addBraille($bookId){
    $braille = new Braille();
    $book = Book::find($bookId);
    $amount = $book->braille()->count();
    if(!$amount)
      $book->bm_date = (date('Y') + 543).date('-m-d H:i:s');

    $book->bm_status = 'ผลิต';
    $book->save();

    $amount = Input::get('numpart');
    $braille->book()->associate($book);
    $braille->produced_date = (date('Y') + 543).date('-m-d H:i:s');
    //$braille->status = 0; // 0 normal,1 broken,2 wait for repeir
    $braille->pages = Input::get('pages');
    $braille->numpart = $amount;
    $braille->examiner()->associate(User::find(Input::get('examiner')['id']));
    $braille->save();

    for($i=1; $i<=$amount; $i++){
      $brailledetail = new Brailledetail();
      $brailledetail->part = $i;
      $brailledetail->date = (date('Y') + 543).date('-m-d H:i:s');
      $brailledetail->braille()->associate($braille);
      $brailledetail->save();
    }

    $book->updateMediaStatus(0);

    return $braille;
  }

  public function addCassette($bookId){
    $book = Book::find($bookId);

    $amount = count(Cassette::where('book_id', $bookId)->get());
    if(!$amount)
      $book->setcs_date = (date('Y') + 543).date('-m-d H:i:s');

    $book->setcs_status = 'ผลิต';
    $book->save();

    $amount = Input::get('numpart');
    $cassette = new Cassette();
    $cassette->produced_date = (date('Y') + 543).date('-m-d H:i:s');
    $cassette->numpart = $amount;
    $cassette->length = Input::get('length');
    $cassette->book()->associate(Book::find($bookId));
    $cassette->save();

    for($i=1; $i<=$amount; $i++){
      $cassetteDetail = new Cassettedetail();
      $cassetteDetail->part = $i;
      $cassetteDetail->status = 0;
      $cassetteDetail->date = (date('Y') + 543).date('-m-d H:i:s');
      $cassetteDetail->cassette()->associate($cassette);
      $cassetteDetail->save();
    }

    $book->updateMediaStatus(1);
    return $cassette;
  }

  public function addDaisy($bookId){
    $book = Book::find($bookId);

    $amount = count(Daisy::where('book_id', $bookId)->get());
    if(!$amount)
      $book->setds_date = (date('Y') + 543).date('-m-d H:i:s');

    $book->setds_status = 'ผลิต';
    $book->save();

    $amount = Input::get('numpart');
    $daisy = new Daisy();
    $daisy->produced_date = (date('Y') + 543).date('-m-d H:i:s');
    $daisy->numpart = $amount;
    $daisy->length = Input::get('length');
    $daisy->book()->associate(Book::find($bookId));
    $daisy->save();

    for($i=1; $i<=$amount; $i++){
      $daisydetail = new Daisydetail();
      $daisydetail->part = $i;
      $daisydetail->status = 0;
      $daisydetail->date = (date('Y') + 543).date('-m-d H:i:s');
      $daisydetail->daisy()->associate($daisy);
      $daisydetail->save();
    }

    $book->updateMediaStatus(2);
    return $daisy;
  }

  public function addCD($bookId){
    $book = Book::find($bookId);

    $amount = count(CD::where('book_id', $bookId)->get());
    if(!$amount)
      $book->setcd_date = (date('Y') + 543).date('-m-d H:i:s');

    $book->setcd_status = 'ผลิต';
    $book->save();

    $amount = Input::get('numpart');
    $cd = new CD();
    $cd->produced_date = (date('Y') + 543).date('-m-d H:i:s');
    $cd->book()->associate(Book::find($bookId));
    $cd->numpart = $amount;
    $cd->length = Input::get('length');
    $cd->save();

    for($i=1; $i<=$amount; $i++){
      $cddetail = new Cddetail();
      $cddetail->part = $i;
      $cddetail->status = 0;
      $cddetail->date = (date('Y') + 543).date('-m-d H:i:s');
      $cddetail->cd()->associate($cd);
      $cddetail->save();
    }

    $book->updateMediaStatus(3);
    return $cd;
  }

  public function addDVD($bookId){
    $book = Book::find($bookId);

    $amount = count(DVD::where('book_id', $bookId)->get());
    if(!$amount)
      $book->setdvd_date = (date('Y') + 543).date('-m-d H:i:s');
    $book->save();

    $amount = Input::get('numpart');
    $dvd = new DVD();
    $dvd->produced_date = (date('Y') + 543).date('-m-d H:i:s');
    $dvd->book()->associate(Book::find($bookId));
    $dvd->numpart = $amount;
    $dvd->length = Input::get('length');
    $dvd->save();

    for($i=1; $i<=$amount; $i++){
      $dvddetail = new Dvddetail();
      $dvddetail->part = $i;
      $dvddetail->status = 0;
      $dvddetail->date = (date('Y') + 543).date('-m-d H:i:s');
      $dvddetail->dvd()->associate($dvd);
      $dvddetail->save();
    }

    $book->updateMediaStatus(4);
    return $dvd;
  }

  /* Getter */

  public function getBraille($bid,$id){
    $book =Book::find($bid);
    $braille = Braille::find($id);
    $brailledetail = Brailledetail::where('braille_id', '=', $braille->id)->get();
    foreach ($brailledetail as $key => $item)
      $item->date = date_format(date_create($item->date), 'd/m/Y');

    return View::make('library.media.braille')->with(array('book'=>$book,'item'=>$braille, 'detail'=>$brailledetail, 'bid'=>$bid));
  }

  public function getCassette($bid,$id){
    $book =Book::find($bid);
    $cassette = Cassette::find($id);
    $cassettedetail = Cassettedetail::where('cassette_id','=',$cassette ->id)->get();
    foreach ($cassettedetail as $key => $item)
      $item->date = date_format(date_create($item->date), 'd/m/Y');

    return View::make('library.media.cassette')->with(array('book'=>$book,'item'=>$cassette,'detail'=>$cassettedetail,'bid'=>$bid));
  }

  public function getDaisy($bid,$id){
    $book =Book::find($bid);
    $daisy = Daisy::find($id);
    $diasydetail = Daisydetail::where('daisy_id','=',$daisy ->id)->get();
    foreach ($diasydetail as $key => $item)
      $item->date = date_format(date_create($item->date), 'd/m/Y');

    return View::make('library.media.daisy')->with(array('book'=>$book,'item'=>$daisy,'detail'=>$diasydetail,'bid'=>$bid));
  }

  public function getCD($bid,$id){
    $book =Book::find($bid);
    $cd = CD::find($id);
    $cddetail = Cddetail::where('cd_id','=',$cd ->id)->get();
    foreach ($cddetail as $key => $item)
      $item->date = date_format(date_create($item->date), 'd/m/Y');

    return View::make('library.media.cd')->with(array('book'=>$book,'item'=>$cd,'detail'=>$cddetail,'bid'=>$bid));
  }

  public function getDVD($bid,$id){
    $book =Book::find($bid);
    $dvd = DVD::find($id);
    $dvddetail = Dvddetail::where('dvd_id','=',$dvd ->id)->get();
    foreach ($dvddetail as $key => $item)
      $item->date = date_format(date_create($item->date), 'd/m/Y');

    return View::make('library.media.dvd')->with(array('book'=>$book,'item'=>$dvd,'detail'=>$dvddetail,'bid'=>$bid));
  }

  public function editMedia() {
    $input = Input::all();
    if($input['media_type'] == 'braille') {
      $media = Braille::find($input['id']);
      $media->pages = $input['pages'];
      $media->examiner()->associate(User::find($input['examiner']['id']));
    }
    else {
      if($input['media_type'] == 'cassette')
        $media = Cassette::find($input['id']);
      else if($input['media_type'] == 'cd')
        $media = CD::find($input['id']);
      else if($input['media_type'] == 'daisy')
        $media = Daisy::find($input['id']);
      else
        $media = DVD::find($input['id']);
      $media->length = $input['length'];
    }
    $media->save();
    $submediaRangeID = $this->editAmountMediaPart($input['media_type'], $input['id'], $input['numpart']);

    return array("submedia_id" => $submediaRangeID);
  }

  public function editAmountMediaPart($media_type, $media_id, $amount) {
    $mediaClassName['braille'] = 'Braille';
    $mediaClassName['cassette'] = 'Cassette';
    $mediaClassName['cd'] = 'CD';
    $mediaClassName['daisy'] = 'Daisy';
    $mediaClassName['dvd'] = 'DVD';
    $detailClassName['braille'] = 'Brailledetail';
    $detailClassName['cassette'] = 'Cassettedetail';
    $detailClassName['cd'] = 'Cddetail';
    $detailClassName['daisy'] = 'Daisydetail';
    $detailClassName['dvd'] = 'Dvddetail';
    $media = $mediaClassName[$media_type]::find($media_id);
    $lastItem = $detailClassName[$media_type]::where($media_type.'_id', '=', $media_id)->get();
    $currentAmount = $lastItem->last()->part;
    if($amount - $currentAmount > 0) {
      for($i=$currentAmount + 1; $i<=$amount; $i++){
        $detail = new $detailClassName[$media_type]();
        $detail->part = $i;
        $detail->status = 0;
        $detail->date = (date('Y') + 543).date('-m-d H:i:s');
        if($media_type == 'braille')
          $detail->braille()->associate($media);
        else if($media_type == 'cassette')
          $detail->cassette()->associate($media);
        else if($media_type == 'cd')
          $detail->cd()->associate($media);
        else if($media_type == 'daisy')
          $detail->daisy()->associate($media);
        else
          $detail->dvd()->associate($media);
        $detail->save();
      }
    }
    else {
      for($i=$currentAmount; $i>$amount; $i--){
        $brailledetail = $detailClassName[$media_type]::where($media_type.'_id', '=', $media_id)->where('part', '=', $i);
        $brailledetail->delete();
      }
    }
    $media->numpart = $amount;
    $media->save();
    return $media->getSubmediaRangeID();
  }

  /* Setter */

  public function setDVD($bookId,$dvdId){
    $input = Input::all();
    $dvdDetail = Dvddetail::where('dvd_id' ,$dvdId)->get();
    $i = 0;

    foreach($dvdDetail as $details) {
      $details->status = $input['status'][$i];
      $details->notes = $input['note'][$i];

      if($input['date'][$i]) {
        $dateTmp = date_create_from_format('d/m/Y', $input['date'][$i]);
        $details->date = date_format($dateTmp, 'Y-m-d H:i:s');
      }
      else
        $details->date = (date('Y') + 543).date('-m-d H:i:s');

      $i++;
      $details->save();
    }

    return Redirect::to(url('book/'.$bookId.'#dvd'));
  }

  public function setCassette($bookId,$casseteId){
    $input = Input::all();
    $cassetteDetail = Cassettedetail::where('cassette_id' ,$casseteId)->get();
    $i = 0;

    foreach($cassetteDetail as $details) {
      $details->status = $input['status'][$i];
      $details->notes = $input['note'][$i];

      if($input['date'][$i]) {
        $dateTmp = date_create_from_format('d/m/Y', $input['date'][$i]);
        $details->date = date_format($dateTmp, 'Y-m-d H:i:s');
      }
      else
        $details->date = (date('Y') + 543).date('-m-d H:i:s');

      $i++;
      $details->save();
    }

    return Redirect::to(url('book/'.$bookId.'#cassette'));
  }

  public function setCD($bookId,$cdId){
    $input = Input::all();
    $cdDetail = Cddetail::where('cd_id' ,$cdId)->get();
    $i = 0;

    foreach($cdDetail as $details) {
      $details->status = $input['status'][$i];
      $details->notes = $input['note'][$i];
      $details->track_fr = $input['track_fr'][$i];
      $details->track_to = $input['track_to'][$i];

      if($input['date'][$i]) {
        $dateTmp = date_create_from_format('d/m/Y', $input['date'][$i]);
        $details->date = date_format($dateTmp, 'Y-m-d H:i:s');
      }
      else
        $details->date = (date('Y') + 543).date('-m-d H:i:s');

      $i++;
      $details->save();
    }
    return Redirect::to(url('book/'.$bookId.'#cd'));
  }

  public function setDaisy($bookId,$daisyId){
    $input = Input::all();
    $daisyDetail = Daisydetail::where('daisy_id' ,$daisyId)->get();
    $i = 0;

    foreach($daisyDetail as $details) {
      $details->status = $input['status'][$i];
      $details->notes = $input['note'][$i];
      $details->track_fr = $input['track_fr'][$i];
      $details->track_to = $input['track_to'][$i];

      if($input['date'][$i]) {
        $dateTmp = date_create_from_format('d/m/Y', $input['date'][$i]);
        $details->date = date_format($dateTmp, 'Y-m-d H:i:s');
      }
      else
        $details->date = (date('Y') + 543).date('-m-d H:i:s');

      $i++;
      $details->save();
    }

    return Redirect::to(url('book/'.$bookId.'#daisy'));
  }

  public function setBraille($bookId,$brailleId){
    $input = Input::all();
    $brailleDetail = Brailledetail::where('braille_id', $brailleId)->get();

    $i = 0;
    foreach($brailleDetail as $details) {
      $details->status = $input['status'][$i];
      $details->notes = $input['note'][$i];

      if($input['date'][$i]) {
        $dateTmp = date_create_from_format('d/m/Y', $input['date'][$i]);
        $details->date = date_format($dateTmp, 'Y-m-d H:i:s');
      }
      else
        $details->date = (date('Y') + 543).date('-m-d H:i:s');

      $i++;
      $details->save();
    }
    return Redirect::to(url('book/'.$bookId.'#braille'));
  }

  public function isAnyBorrower()
  {
    $book_id = Input::get('book_id');
    $media_type = Input::get('media_type');
    $media_type = ($media_type == 'cd') ? 'CD' : $media_type;
    $media_type = ($media_type == 'dvd') ? 'DVD' : $media_type;
    $media_type = ucfirst($media_type);
    $media = $media_type::where('book_id', '=', $book_id)->get();

    if(!count($media))
      return 'no media';
    
    foreach ($media as $key => $item) {
      if($item->reserved)
        return 'true';
    }
    return 'false';
  }

  public function removeAllDvd($bookId){

    $dvds = DVD::where('book_id',$bookId)->get();
    foreach ($dvds as $item) {
      $item->borrow()->delete();
      $item->detail()->delete();
      $item->delete();
    }

    $book = Book::find($bookId);
    $book->setdvd_date = date_create('0000-00-00 00:00:00');
    $book->save();
    $book->updateMediaStatus(4);
    return array('status' => 'success');
  }

  public function removeAllCd($bookId){

    $cds = CD::where('book_id',$bookId)->get();
    foreach ($cds as $item) {
      $item->borrow()->delete();
      $item->detail()->delete();
      $item->delete();
    }

    $book = Book::find($bookId);
    $book->setcd_date = date_create('0000-00-00 00:00:00');
    $book->save();
    $book->updateMediaStatus(3);
    return array('status' => 'success');
  }

  public function removeAllDaisy($bookId){

    $daisys = Daisy::where('book_id',$bookId)->get();
    foreach ($daisys as $item) {
      $item->borrow()->delete();
      $item->detail()->delete();
      $item->delete();
    }

    $book = Book::find($bookId);
    $book->setds_date = date_create('0000-00-00 00:00:00');
    $book->save();
    $book->updateMediaStatus(2);
    return array('status' => 'success');
  }

  public function removeAllCassette($bookId){
    $items = Cassette::where('book_id',$bookId)->get();
    foreach ($items as $item) {
      $item->borrow()->delete();
      $item->detail()->delete();
      $item->delete();
    }

    $book = Book::find($bookId);
    $book->setcs_date = date_create('0000-00-00 00:00:00');
    $book->save();
    $book->updateMediaStatus(1);
    return array('status' => 'success');
  }

  public function removeAllBraille($bookId){
    $items = Braille::where('book_id',$bookId)->get();
    foreach ($items as $item) {
      $item->borrow()->delete();
      $item->detail()->delete();
      $item->delete();
    }

    $book = Book::find($bookId);
    $book->bm_date = date_create('0000-00-00 00:00:00');
    $book->save();
    $book->updateMediaStatus(0);
    return array('status' => 'success');
  }

  public function removeSelectedCassette($bookId,$cassetteId){
    $item = Cassette::find($cassetteId);
    $item->borrow()->delete();
    $item->detail()->delete();
    $item->delete();

    if(!count(Cassette::where('book_id',$bookId)->get())) {
      $book = Book::find($bookId);
      $book->setcs_date = date_create('0000-00-00 00:00:00');
      $book->save();
      $book->updateMediaStatus(1);
    }
    return array('status' => 'success');
  }

  public function removeSelectedCd($bookId,$cdId){
    $item = CD::find($cdId);
    $item->borrow()->delete();
    $item->detail()->delete();
    $item->delete();

    if(!count(CD::where('book_id',$bookId)->get())) {
      $book = Book::find($bookId);
      $book->setcd_date = date_create('0000-00-00 00:00:00');
      $book->save();
      $book->updateMediaStatus(3);
    }
    return array('status' => 'success');
  }
  public function removeSelectedDvd($bookId,$dvdId){
    $item = DVD::find($dvdId);
    $item->borrow()->delete();
    $item->detail()->delete();
    $item->delete();

    if(!count(DVD::where('book_id',$bookId)->get())) {
      $book = Book::find($bookId);
      $book->setdvd_date = date_create('0000-00-00 00:00:00');
      $book->save();
      $book->updateMediaStatus(4);
    }
    return array('status' => 'success');
  }
  public function removeSelectedBraille($bookId,$brailleId){
    $item = Braille::find($brailleId);
    $item->borrow()->delete();
    $item->detail()->delete();
    $item->delete();

    if(!count(Braille::where('book_id',$bookId)->get())) {
      $book = Book::find($bookId);
      $book->bm_date = date_create('0000-00-00 00:00:00');
      $book->save();
      $book->updateMediaStatus(0);
    }
    return array('status' => 'success');
  }

  public function removeSelectedDaisy($bookId,$daisyId){
    $item = Daisy::find($daisyId);
    $item->borrow()->delete();
    $item->detail()->delete();
    $item->delete();

    if(!count(Daisy::where('book_id',$bookId)->get())) {
      $book = Book::find($bookId);
      $book->setds_date = date_create('0000-00-00 00:00:00');
      $book->save();
      $book->updateMediaStatus(2);
    }
    return array('status' => 'success');
  }
}

<?php
class BookController extends Controller{

  public function newBook(){
    return View::make('library.book.add');
  }

  public function delete($bid)
  {
    $book = Book::find($bid);
    $book->removeAllMedia();
    $book->removeAllProd();
    $book->delete();
    return Redirect::to('/');
  }

  public function postBook(){
    $Book = new Book;
        //$Book = Book::where('id','=','2')->count();
    $Book->number = Input::get('number');
    $Book->b_no = Input::get('b_no');
    $Book->c_no = Input::get('c_no');
    $Book->cd_no = Input::get('cd_no');
    $Book->d_no = Input::get('d_no');
    $Book->dvd_no = Input::get('dvd_no');
    $Book->isbn = Input::get('isbn');
    $Book->title = Input::get('title');
    $Book->title_eng = Input::get('title_eng');
    $Book->author = Input::get('author');
    $Book->translate = Input::get('translate');
    $Book->grade = Input::get('grade');
    $Book->abstract = Input::get('abstract');
    $Book->book_type = Input::get('book_type');
    $Book->produce_no = Input::get('produce_no');
    $Book->pub_no = Input::get('pub_no');
    $Book->pub_year = Input::get('pub_year');
    $Book->publisher = Input::get('publisher');
    $Book->created_at = (date("Y") + 543).date("-m-d H:i:s");
    $Book->save();
    return Redirect::to('book/add');
  }

  public function getBook($bid){
    $bookEloquent = Book::find($bid);

    if($bookEloquent==NULL){
      App::abort(404);
    }

    $book['title']          = $bookEloquent->title;
    $book['title_eng']      = $bookEloquent->title_eng;
    $book['author']         = $bookEloquent->author ;
    $book['translate']      = $bookEloquent->translate ;
    if($bookEloquent->registered_date == NULL || $bookEloquent->registered_date == "0000-00-00 00:00:00")
      $book['regis_date'] = "-";
    else
      $book['regis_date']   = date_format(date_create($bookEloquent->registered_date), 'd-m-Y');
    $book['publisher']      = $bookEloquent->publisher ;
    $book['pub_no']         = $bookEloquent->pub_no ;
    $book['pub_year']       = $bookEloquent->pub_year ;

    $book['produce_no']     = $bookEloquent->produce_no ;
    $book['booktype']       = $bookEloquent->book_type ;
    $book['abstract']       = $bookEloquent->abstract ;

    $book['isbn']          = $bookEloquent->isbn ;
    $book['id']            = $bookEloquent->id;
    $book['grade']         = $bookEloquent->grade ;

    $book['b_number']      = count($bookEloquent->braille);
    $book['cs_number']     = count($bookEloquent->cassette);
    $book['ds_number']     = count($bookEloquent->daisy);
    $book['cd_number']     = count($bookEloquent->cd);
    $book['dvd_number']    = count($bookEloquent->dvd);

    $book['bm_status']     = ($bookEloquent->bm_status == 2) ? 'กำลังผลิต' : $this->getWordStatus($bookEloquent->bm_status);
    $book['bm_date']       = $this->formatDate($bookEloquent->bm_date);
    $book['bm_no']         = $bookEloquent->bm_no;
    $book['bm_note']       = $bookEloquent->bm_note ;
    $book['setcs_status']  = $this->getWordStatus($bookEloquent->setcs_status) ;
    $book['setcs_date']    = ($bookEloquent->setcs_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcs_date), 'd-m-Y');
    $book['setcm_no']         = $bookEloquent->setcm_no;
    $book['setcs_note']    = $bookEloquent->setcs_note ;
    $book['setds_status']  = $this->getWordStatus($bookEloquent->setds_status) ;
    $book['setds_date']    = ($bookEloquent->setds_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setds_date), 'd-m-Y');
    $book['setdm_no']         = $bookEloquent->setdm_no;
    $book['setds_note']    = $bookEloquent->setds_note ;
    $book['setcd_status']  = $this->getWordStatus($bookEloquent->setcd_status) ;
    $book['setcd_date']    = ($bookEloquent->setcd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcd_date), 'd-m-Y');
    $book['setcdm_no']        = $bookEloquent->setcdm_no;
    $book['setcd_note']    = $bookEloquent->setcd_note ;
    $book['setdvd_status'] = $this->getWordStatus($bookEloquent->setdvd_status) ;
    $book['setdvd_date']   = ($bookEloquent->setdvd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setdvd_date), 'd-m-Y');
    $book['setdvdm_no']       = $bookEloquent->setdvdm_no;
    $book['setdvd_note']   = $bookEloquent->setdvd_note ;
    if($bookEloquent->created_at == "-0001-11-30 00:00:00")
      $book['created_at'] = "-";
    else
      $book['created_at']   = date_format(date_create($bookEloquent->created_at), 'd-m-Y');

    $number        = $bookEloquent->number;
    $all_media     = "(";
    $media_char    = array('B', 'C', 'CD', 'D', 'DVD');
    $media_no      = array($bookEloquent->b_no, $bookEloquent->c_no, $bookEloquent->cd_no, $bookEloquent->d_no, $bookEloquent->dvd_no);
    for($i = 0; $i < 5; $i++) {
      if($media_no[$i] != null)
        $all_media = $all_media.$media_char[$i].$media_no[$i].", ";
    }

    $all_media = substr($all_media, 0, -2).")";
    $all_media = ($all_media == ")") ? "" : $all_media;
    $arrOfdata['book']=$book;
    $arrOfdata['number'] = $number;
    $arrOfdata['all_media'] = $all_media;

    return View::make('library.book.view')->with($arrOfdata);
  }

  private function formatDate($date) {
    return (in_array($date, array("0000-00-00 00:00:00", null))) ? "ยังไม่ได้ระบุ" : date_format(date_create($date), 'd-m-Y');
  }

  public function getEdit($bid){
    $bookEloquent = Book::find($bid);

    $book['number']        = $bookEloquent->number; 
    $book['b_no']          = $bookEloquent->b_no;
    $book['c_no']          = $bookEloquent->c_no;
    $book['cd_no']         = $bookEloquent->cd_no;
    $book['d_no']          = $bookEloquent->d_no;
    $book['dvd_no']        = $bookEloquent->dvd_no;  
    $book['title']         = $bookEloquent->title;
    $book['title_eng']     = $bookEloquent->title_eng;
    $book['author']        = $bookEloquent->author ;
    $book['translate']     = $bookEloquent->translate ;
    //$book['regis_date']    = date_format(date_create($bookEloquent->registered_date), 'd/m/Y');
    if($bookEloquent->registered_date == NULL || $bookEloquent->registered_date == "0000-00-00 00:00:00")
      $book['regis_date'] = null;
    else
      $book['regis_date']   = date_format(date_create($bookEloquent->registered_date), 'd-m-Y');
    $book['publisher']     = $bookEloquent->publisher ;
    $book['pub_no']        = $bookEloquent->pub_no ;
    $book['pub_year']      = $bookEloquent->pub_year ;
    $book['produce_no']    = $bookEloquent->produce_no ;
    $book['btype']         = $bookEloquent->book_type ;
    $book['grade']         = $bookEloquent->grade ;
    $book['isbn']          = $bookEloquent->isbn ;
    $book['id']            = $bookEloquent->id; //TODO : Add validator to check id before change it
    $book['abstract']      = $bookEloquent->abstract ;
    $book['bm_status']     = ($bookEloquent->bm_status == 2) ? "กำลังผลิต" : $this->getWordStatus($bookEloquent->bm_status);
    $book['bm_date']       = ($bookEloquent->bm_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->bm_date), 'd/m/Y');
    $book['bm_no']         = $bookEloquent->bm_no;
    $book['bm_note']       = $bookEloquent->bm_note ;
    $book['setcs_status']  = $this->getWordStatus($bookEloquent->setcs_status);
    $book['setcs_date']    = ($bookEloquent->setcs_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcs_date), 'd/m/Y');
    $book['setcm_no']      = $bookEloquent->setcm_no;
    $book['setcs_note']    = $bookEloquent->setcs_note ;
    $book['setds_status']  = $this->getWordStatus($bookEloquent->setds_status);
    $book['setds_date']    = ($bookEloquent->setds_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setds_date), 'd/m/Y');
    $book['setdm_no']      = $bookEloquent->setdm_no;
    $book['setds_note']    = $bookEloquent->setds_note ;
    $book['setcd_status']  = $this->getWordStatus($bookEloquent->setcd_status);
    $book['setcd_date']    = ($bookEloquent->setcd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcd_date), 'd/m/Y');
    $book['setcdm_no']     = $bookEloquent->setcdm_no;
    $book['setcd_note']    = $bookEloquent->setcd_note ;
    $book['setdvd_status'] = $this->getWordStatus($bookEloquent->setdvd_status);
    $book['setdvd_date']   = ($bookEloquent->setdvd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setdvd_date), 'd/m/Y');
    $book['setdvdm_no']       = $bookEloquent->setdvdm_no;
    $book['setdvd_note']   = $bookEloquent->setdvd_note ;
    $arrOfdata['book']     = $book;
    return View::make('library.book.edit')->with($arrOfdata);
  }

  public function postEdit($bid){
    $book = Book::find($bid);
    $book->number     = Input::get('number');
    $book->b_no       = Input::get('b_no');
    $book->c_no       = Input::get('c_no');
    $book->cd_no      = Input::get('cd_no');
    $book->d_no       = Input::get('d_no');
    $book->dvd_no     = Input::get('dvd_no');
    $book->title      = Input::get('title');
    $book->title_eng  = Input::get('title_eng');
    $book->author     = Input::get('author');
    $book->translate  = Input::get('translate');
    $dateTmp = date_create_from_format('d/m/Y', Input::get('regis_date'));
    if(Input::get('regis_date') == null)
      $book->registered_date  = "0000-00-00 00:00:00";
    else
      $book->registered_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    $book->publisher  = Input::get('publisher');
    $book->pub_no     = Input::get('pub_no');
    $book->pub_year   = Input::get('pub_year');
    $book->produce_no = Input::get('produce_no');
    $book->book_type  = Input::get('btype');
    $book->abstract   = Input::get('abstract');
    $book->isbn       = Input::get('isbn');
      //$book['id']         = Input::get('id'); //TODO : make ID Validator
    $book->grade      = Input::get('grade');

    $book->bm_note    = Input::get('bm_note');
    $book->bm_no    = Input::get('bm_no');
    if(Input::get('bm_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('bm_date'));
      $book->bm_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->bm_date  = "0000-00-00 00:00:00";

    $book->setcs_note    = Input::get('cs_note');
    $book->setcm_no     = Input::get('setcm_no');
    if(Input::get('cs_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('cs_date'));
      $book->setcs_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setcs_date  = "0000-00-00 00:00:00";

    $book->setds_note    = Input::get('ds_note');
    $book->setdm_no     = Input::get('setdm_no');
    if(Input::get('ds_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('ds_date'));
      $book->setds_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setds_date  = "0000-00-00 00:00:00";

    $book->setcd_note    = Input::get('cd_note');
    $book->setcdm_no     = Input::get('setcdm_no');
    if(Input::get('cd_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('cd_date'));
      $book->setcd_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setcd_date  = "0000-00-00 00:00:00";

    $book->setdvd_note   = Input::get('dvd_note');
    $book->setdvdm_no     = Input::get('setdvdm_no');
    if(Input::get('dvd_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('dvd_date'));
      $book->setdvd_date = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setdvd_date = "0000-00-00 00:00:00";
      // TODO : add Validator here

    $book->updated_at = (date("Y") + 543).date("-m-d H:i:s");

    $book->save();
    return Redirect::to("/book/$bid");
  }

    // Search getter
  public function SearchFromAttr(){
    $type = Input::get('search_type');
    $input = Input::get('search_value');
    $offset = Input::get('data_offset');
    if($type == "title"){
      $query = Book::where("title","LIKE","%".$input."%");
    }else if($type == "author"){
      $query = Book::where("author","LIKE","%".$input."%");
    }else if($type == "translate"){
      $query = Book::where("translate","LIKE","%".$input."%");
    }else if($type == "isbn"){
      $query = Book::where("isbn","=","LIKE","%".$input."%");
    }else if($type == "id"){
      $query = Book::where("id","=",$input);
    }else{
      return "ERROR :: Wrong Format !!";
    }
    $obj = $query->take(6)->offset($offset)->get();
    $count = $query->count();
          //return View::make('library.index',array('books' => $obj ));
    return array($obj,$count);
  }
        // For Ajax search Call (INCOMPLETE)
  public function getDatatable()
  {
    return Datatable::collection(Book::all(array('title','author')))
    ->showColumns('title', 'author')
    ->searchColumns('title')
    ->orderColumns('title','author')
    ->make();
  }

      // Enum media status helper
  public function getWordStatus($status){
    $enum = array('ไม่ผลิต','ผลิต','จองอ่าน','กำลังผลิต', 'error');
    if($status > 3 || $status < 0)$status=4;
    return $enum[(int)$status];
  }

  public function postProdAdd($bid)
  {
    $data = Input::all();
    $bp = new BookProd;
    $bp->book_id = $bid;
    $bp->media_type = $data["media_type"];
    $bp->action = $data["action"];
    $bp->act_date = $data['act_date'];
    $bp->finish_date = $data['finish_date'];
    $bp->user()->associate(User::find($data['actioner']['id']));
    $book = Book::find($bid);

    if($bp->action == 5) {
      $activeProds = $book->prod()->where('media_type', '=', $data["media_type"])->where('status', '=', Status::ACTIVE)->get();
      foreach ($activeProds as $prod) {
        $prod->status = Status::DELETE;
        $prod->save();
      }
    }
    if($bp->save()) {
      $bp->user()->associate(User::find(Input::get('actioner')['id']));
      $bp->save();
      $book->updateAllMediaStatus();
      return "success";
    }
    return "failed";
  }

  public function getAllProd($bid)
  {
    $book = Book::find($bid);
    $prodCollection = array();
    $prodCollection['braille'] = $book->prod()->where('media_type', '=', 0)->where('status', '=', Status::ACTIVE)->get();
    $prodCollection['cassette'] = $book->prod()->where('media_type', '=', 1)->where('status', '=', Status::ACTIVE)->get();
    $prodCollection['daisy'] = $book->prod()->where('media_type', '=', 2)->where('status', '=', Status::ACTIVE)->get();
    $prodCollection['cd'] = $book->prod()->where('media_type', '=', 3)->where('status', '=', Status::ACTIVE)->get();
    $prodCollection['dvd'] = $book->prod()->where('media_type', '=', 4)->where('status', '=', Status::ACTIVE)->get();

    foreach ($prodCollection as $prods){
      foreach ($prods as $prod)
        $prod->actioner = $prod->user()->get()->first();
    }
    return $prodCollection;
  }

  public function getProd($id)
  {
    $book = Book::find($id);
    $bp = $this->addLastStatusToProd($book->prod);
    $bp['book_id'] = $book['id'];
    return $bp;
  }

  public function getLastProdStatus($bid)
  {
    $book = Book::find($bid);
    return $book->getLastProdStatus(Input::get('media_type'));
  }
  
  public function postProdedit()
  {
    $data = Input::all();
    $bp = BookProd::find($data["id"]);
    $bp->act_date = $data['act_date'];
    $bp->finish_date = $data['finish_date'];
    $bp->user()->associate(User::find($data['actioner']['id']));
    //TODO check data intregrity

    if($bp->save())
      return "success";
    return "failed";
  }

  public function deleteProd($bid)
  {
    //$bp = BookProd::find($bpId);
    $book = Book::find($bid);
    $prod = $book->prod()->where('media_type', '=', Input::get('media_type'))->where('status', '=', Status::ACTIVE)->get()->last();
    $media_type = $prod->media_type;

    if($book->countMedia($media_type)) { //cannot delete prod status until delete involved media
      $data['status'] = 'failed';
      $data['status-description'] = 'remove all media before delete production status.';
      return $data;
    }

    $prod->status = Status::DELETE; //soft delete
    $prod->save();
    
    $productionStatus = $book->updateMediaStatus($media_type);

    $data['media_type'] = Book::getDefinedMediaWord($media_type);
    if($media_type == 0 && $productionStatus == 2)
      $data['status'] = 'กำลังผลิต';
    else
      $data['status'] = $this->getWordStatus($productionStatus);
    return $data;
  }

  public function addLastStatusToProd($prod)
  {
    $lastAction = array(-1, -1, -1, -1, -1);
    foreach ($prod as $key => $item) {
      if($item->action > $lastAction[$item->media_type])
        $lastAction[$item->media_type] = $item->action;
    }
    foreach ($prod as $key => $item) {
      if($item->action == $lastAction[$item->media_type])
        $item->isLastStatus = true;
      else
        $item->isLastStatus = false;
    }
    return $prod;
  }
}

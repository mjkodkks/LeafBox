<?php
class BookController extends Controller{

  public function newBook(){
    return View::make('library.book.add');
  }

  public function postBook(){
    $Book = new Book;
        //$Book = Book::where('id','=','2')->count();
    $Book->isbn = Input::get('isbn');
    $Book->title = Input::get('title');
    $Book->author = Input::get('author');
    $Book->translate = Input::get('translate');
    $Book->grade = Input::get('grade');
    $Book->abstract = Input::get('abstract');
    $Book->book_type = Input::get('book_type');
    $Book->produce_no = Input::get('produce_no');
    $Book->pub_no = Input::get('pub_no');
    $Book->pub_year = Input::get('pub_year');
    $Book->publisher = Input::get('publisher');
    $Book->save();
    return Redirect::to('book/add');
  }

  public function getBook($bid){
    $bookEloquent = Book::find($bid);

    if($bookEloquent==NULL){
      App::abort(404);
    }

    $field[0]='ชื่อเรื่อง';
    $field[1]='ผู้แต่ง';
    $field[2]='ผู้แปล';
    $field[3]='วันลงทะเบียน';
    $field[4]='สำนักพิมพ์';
    $field[5]='พิมพ์ครั้งที่';
    $field[6]='ปีที่พิมพ์';

    $field[7]='ทะเบียนผลิต';
    $field[8]='ประเภทหนังสือ';
    $field[9]='เนื้อเรื่องย่อ';
    $field[10]='ISBN';
    $field[11]='ID';
    $field[12]='ระดับ';

    $field[13]='จำนวนหนังสือเบรลล์';
    $field[14]='จำนวนเทปคาสเส็ท';
    $field[15]='จำนวนเดซี่';
    $field[16]='จำนวน CD';
    $field[17]='จำนวน DVD';

    $field[18]='สถานะของเบรลล์';
    $field[19]='หมายเหตุ';
    $field[20]='เมื่อ';
    $field[21]='สถานะของคาสเส็ท';
    $field[22]='หมายเหตุ';
    $field[23]='เมื่อ';
    $field[24]='สถานะของเดซี่';
    $field[25]='หมายเหตุ';
    $field[26]='เมื่อ';
    $field[27]='สถานะของ CD';
    $field[28]='หมายเหตุ';
    $field[29]='เมื่อ';
    $field[30]='สถานะของ DVD';
    $field[31]='หมายเหตุ';
    $field[32]='เมื่อ';
    $field[33]='เพิ่มเมื่อ';

    $book['title']         =  $bookEloquent->title;
    $book['author']        = $bookEloquent->author ;
    $book['translate']     = $bookEloquent->translate ;
    $book['regis_date']     = $bookEloquent->regis_date ;
    $book['publisher']     = $bookEloquent->publisher ;
    $book['pub_no']     = $bookEloquent->pub_no ;
    $book['pub_year']     = $bookEloquent->pub_year ;

    $book['produce_no']   = $bookEloquent->produce_no ;
    $book['btype']   = $bookEloquent->btype ;
    $book['abstract']   = $bookEloquent->abstract ;

    $book['isbn']          = $bookEloquent->isbn ;
    $book['id']            = $bookEloquent->id ;
    $book['grade']         = $bookEloquent->grade ;

    $book['b_number']     = count($bookEloquent->braille);
    $book['cs_number']     = count($bookEloquent->cassette);
    $book['ds_number']     = count($bookEloquent->daisy);
    $book['cd_number']     = count($bookEloquent->cd);
    $book['dvd_number']     = count($bookEloquent->dvd);

    $book['bm_status']     = $this->getWordStatus($bookEloquent->bm_status) ;
    $book['bm_note']       = $bookEloquent->bm_note ;
    $book['bm_date']       = ($bookEloquent->bm_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->bm_date), 'd-m-Y');
    $book['setcs_status']  = $this->getWordStatus($bookEloquent->setcs_status) ;
    $book['setcs_note']    = $bookEloquent->setcs_note ;
    $book['setcs_date']    = ($bookEloquent->setcs_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcs_date), 'd-m-Y');
    $book['setds_status']  = $this->getWordStatus($bookEloquent->setds_status) ;
    $book['setds_note']    = $bookEloquent->setds_note ;
    $book['setds_date']    = ($bookEloquent->setds_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setds_date), 'd-m-Y');
    $book['setcd_status']  = $this->getWordStatus($bookEloquent->setcd_status) ;
    $book['setcd_note']    = $bookEloquent->setcd_note ;
    $book['setcd_date']    = ($bookEloquent->setcd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcd_date), 'd-m-Y');
    $book['setdvd_status'] = $this->getWordStatus($bookEloquent->setdvd_status) ;
    $book['setdvd_note']   = $bookEloquent->setdvd_note ;
    $book['setdvd_date']   = ($bookEloquent->setdvd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setdvd_date), 'd-m-Y');
    $book['created_at']    = ($bookEloquent->setdvd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setdvd_date), 'd-m-Y');

          //braile
    $braille = Braille::where('book_id','=',$book['id'])->get();
    foreach ($braille as $key => $item) {
      $borrow = Brailleborrow::where('braille_id', '=', $item->id)->get();
      $borrow = $borrow->last();
      $item->borrower = "ไม่มี";
      if(isset($borrow) && $borrow->actual_returned == "0000-00-00 00:00:00")
        $item->borrower = Member::find($borrow->member_id)->name;
    }
          //cassette
    $cassette = Cassette::where('book_id','=',$book['id'])->get();
    foreach ($cassette as $key => $item) {
      $borrow = Cassetteborrow::where('cassette_id', '=', $item->id)->get();
      $borrow = $borrow->last();
      $item->borrower = "ไม่มี";
      if(isset($borrow) && $borrow->actual_returned == "0000-00-00 00:00:00")
        $item->borrower = Member::find($borrow->member_id)->name;
    }
          //daisy
    $daisy = Daisy::where('book_id','=',$book['id'])->get();
    foreach ($daisy as $key => $item) {
      $borrow = Daisyborrow::where('daisy_id', '=', $item->id)->get();
      $borrow = $borrow->last();
      $item->borrower = "ไม่มี";
      if(isset($borrow) && $borrow->actual_returned == "0000-00-00 00:00:00")
        $item->borrower = Member::find($borrow->member_id)->name;
    }
          //cd
    $cd = CD::where('book_id','=',$book['id'])->get();
    foreach ($cd as $key => $item) {
      $borrow = Cdborrow::where('cd_id', '=', $item->id)->get();
      $borrow = $borrow->last();
      $item->borrower = "ไม่มี";
      if(isset($borrow) && $borrow->actual_returned == "0000-00-00 00:00:00")
        $item->borrower = Member::find($borrow->member_id)->name;
    }
          //dvd
    $dvd = DVD::where('book_id','=',$book['id'])->get();
    foreach ($dvd as $key => $item) {
      $borrow = Dvdborrow::where('dvd_id', '=', $item->id)->get();
      $borrow = $borrow->last();
      $item->borrower = "ไม่มี";
      if(isset($borrow) && $borrow->actual_returned == "0000-00-00 00:00:00")
        $item->borrower = Member::find($borrow->member_id)->name;
    }

    $arrOfdata['field']=$field;
    $arrOfdata['book']=$book;

    $arrOfdata['braille']=$braille;
    $arrOfdata['cassette']=$cassette;
    $arrOfdata['daisy']=$daisy;
    $arrOfdata['cd']=$cd;
    $arrOfdata['dvd']=$dvd;
    $arrOfdata['bookEloquent'] = $bookEloquent;
    return View::make('library.book.view')->with($arrOfdata);
  }

  public function getEdit($bid){
    $bookEloquent = Book::find($bid);
    $label[0]='ชื่อเรื่อง';
    $field[0]='title';
    $label[1]='ผู้แต่ง';
    $field[1]='author';
    $label[2]='ผู้แปล';
    $field[2]='translate';
    $label[3]='วันลงทะเบียน';
    $field[3]='regis_date';
    $label[4]='สำนักพิมพ์';
    $field[4]='publisher';
    $label[5]='พิมพ์ครั้งที่';
    $field[5]='pub_no';
    $label[6]='ปีที่พิมพ์';
    $field[6]='pub_year';
    $label[7]='ทะเบียนผลิต';
    $field[7]='produce_no';
    $label[8]='ประเภทหนังสือ';
    $field[8]='btype';
    $label[9]='หนังสือระดับ';
    $field[9]='grade';

    $label[10]='ISBN';
    $field[10]='isbn';
    $label[11]='เลขหนังสือ';
    $field[11]='id';

    $label[12]='เนื้อเรื่องย่อ';
    $field[12]='abstract';

    $label[13]='สถานะของเบรลล์';
    $field[13]='bm_status';
    $label[14]='เมื่อ';
    $field[14]='bm_date';
    $label[15]='หมายเหตุ';
    $field[15]='bm_note';

    $label[16]='สถานะของคาสเส็ท';
    $field[16]='cs_status';
    $label[18]='หมายเหตุ';
    $field[18]='cs_note';
    $label[17]='เมื่อ';
    $field[17]='cs_date';

    $label[19]='สถานะของเดซี่';
    $field[19]='ds_status';
    $label[21]='หมายเหตุ';
    $field[21]='ds_note';
    $label[20]='เมื่อ';
    $field[20]='ds_date';

    $label[22]='สถานะของซีดี';
    $field[22]='cd_status';
    $label[24]='หมายเหตุ';
    $field[24]='cd_note';
    $label[23]='เมื่อ';
    $field[23]='cd_date';

    $label[25]='สถานะของดีวีดี';
    $field[25]='dvd_status';
    $label[27]='หมายเหตุ';
    $field[27]='dvd_note';
    $label[26]='เมื่อ';
    $field[26]='dvd_date';

    $label[28]='สร้างเมื่อ';
    $field[28]='created_at';

    $book['title']         =  $bookEloquent->title;
    $book['author']        = $bookEloquent->author ;
    $book['translate']     = $bookEloquent->translate ;
    $book['regis_date']     = $bookEloquent->regis_date ;
    $book['publisher']     = $bookEloquent->publisher ;
    $book['pub_no']     = $bookEloquent->pub_no ;
    $book['pub_year']     = $bookEloquent->pub_year ;

    $book['produce_no']   = $bookEloquent->produce_no ;
    $book['btype']   = $bookEloquent->btype ;
    $book['grade']         = $bookEloquent->grade ;


    $book['isbn']          = $bookEloquent->isbn ;
    $book['id']            = $bookEloquent->id;
    $book['abstract']   = $bookEloquent->abstract ;

    $book['bm_status']     = $bookEloquent->bm_status ;
    $book['bm_date']       = ($bookEloquent->bm_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->bm_date), 'd/m/Y');
    $book['bm_note']       = $bookEloquent->bm_note ;
    $book['setcs_status']  = $bookEloquent->setcs_status ;
    $book['setcs_date']    = ($bookEloquent->setcs_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcs_date), 'd/m/Y');
    $book['setcs_note']    = $bookEloquent->setcs_note ;
    $book['setds_status']  = $bookEloquent->setds_status ;
    $book['setds_date']    = ($bookEloquent->setds_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setds_date), 'd/m/Y');
    $book['setds_note']    = $bookEloquent->setds_note ;
    $book['setcd_status']  = $bookEloquent->setcd_status ;
    $book['setcd_date']    = ($bookEloquent->setcd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setcd_date), 'd/m/Y');
    $book['setcd_note']    = $bookEloquent->setcd_note ;
    $book['setdvd_status'] = $bookEloquent->setdvd_status ;
    $book['setdvd_date']   = ($bookEloquent->setdvd_date == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" : date_format(date_create($bookEloquent->setdvd_date), 'd/m/Y');
    $book['setdvd_note']   = $bookEloquent->setdvd_note ;
    $book['created_at']    = ($bookEloquent->created_at == "0000-00-00 00:00:00") ? "ยังไม่ได้ระบุ" :  date_format(date_create($bookEloquent->created_at), 'd/m/Y');

    $arrOfdata['label']=$label;
    $arrOfdata['field']=$field;
    $arrOfdata['book']=$book;
    return View::make('library.book.edit')->with($arrOfdata);
  }

  public function postEdit($bid){
    $book = Book::find($bid);
    $book->title      = Input::get('title');
    $book->author     = Input::get('author');
    $book->translate  = Input::get('translate');
    $book->created_at = Input::get('regis_date');
    $book->publisher  = Input::get('publisher');
    $book->pub_no     = Input::get('pub_no');
    $book->pub_year   = Input::get('pub_year');
    $book->produce_no = Input::get('produce_no');
    $book->book_type      = Input::get('btype');
    $book->abstract   = Input::get('abstract');
    $book->isbn       = Input::get('isbn');
      //$book['id']         = Input::get('id'); //TODO : make ID Validator
    $book->grade      = Input::get('grade');

    $book->bm_status  = Input::get('bm_status');
    $book->bm_note    = Input::get('bm_note');
    if(Input::get('bm_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('bm_date'));
      $book->bm_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->bm_date  = "0000-00-00 00:00:00";

    $book->setcs_status  = Input::get('cs_status');
    $book->setcs_note    = Input::get('cs_note');
    if(Input::get('cs_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('cs_date'));
      $book->setcs_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setcs_date  = "0000-00-00 00:00:00";

    $book->setds_status  = Input::get('ds_status');
    $book->setds_note    = Input::get('ds_note');
    if(Input::get('ds_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('ds_date'));
      $book->setds_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setds_date  = "0000-00-00 00:00:00";

    $book->setcd_status  = Input::get('cd_status');
    $book->setcd_note    = Input::get('cd_note');
    if(Input::get('cd_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('cd_date'));
      $book->setcd_date  = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setcd_date  = "0000-00-00 00:00:00";

    $book->setdvd_status = Input::get('dvd_status');
    $book->setdvd_note   = Input::get('dvd_note');
    if(Input::get('dvd_date')) {
      $dateTmp = date_create_from_format('d/m/Y', Input::get('dvd_date'));
      $book->setdvd_date = date_format($dateTmp, 'Y-m-d H:i:s');
    }
    else
      $book->setdvd_date = "0000-00-00 00:00:00";
      // TODO : add Validator here
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
    $enum = array('ไม่ผลิต','ผลิต','จองอ่าน','ไม่ถูกต้อง');
    if($status == null)$status=3;
    return $enum[(int)$status];
  }

  public function postProdAdd()
  {

    $bp = new BookProd;
    $bp->book_id=Input::get("book_id", null);
    $bp->act_date=Input::get("act_date", null);
    $bp->action=Input::get("action", null);
    $bp->actioner=Input::get("actioner", null);
    $bp->finish_date=Input::get("finish_date", null);
    if ($bp->act_date==""||$bp->action==""||$bp->actioner=="")
      return "failed, null not permit";

    if($bp->save())
      return "success";
    return "failed";
  }

  public function getProd($id)
  {
    $book = Book::find($id);
    $bp = $book->prod;
      //var_dump($bp);
    return $bp;
  }

}

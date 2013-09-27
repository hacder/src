<?php
class mpage{

	var $first_tag = '首页';
	var $last_tag = '尾页';
	var $previous_tag = '上页';
	var $next_tag = '下页';
	var $search_button = '';
	var $no_result = '暂无相关记录   :(';
	var $merpagenum =5;
	
	public function __construct(){
		
	}
	
	public function create_links($url = '', $total_rows = 0, $per_page = 5, $offset = 0, $search = '', $anchor='') {
		
		$output = '';
		if ($total_rows == 0 or $per_page == 0) {
			$output .= "<div class='pageR'>";
			$output .= $this->no_result;
			$output .= '</div>';
		}else{
	
			$num_pages = ceil ( ($total_rows) / $per_page );
			$cur_page = floor( $offset / $per_page ) + 1;
	
			$output .= "<div class='pageR'>";
			$output .="<span>总记录<i>".$total_rows."</i>条</span>";
			$output .="<span>共<i>".$num_pages."</i>页</span>";
	
			//init tags
			$first_tag = $this->_tag_href ( $url, 0, $this->first_tag, $search ,$anchor);
			$last_tag = $this->_tag_href ( $url, ($num_pages - 1) * $per_page, $this->last_tag, $search ,$anchor);
			$previous_tag = $this->_tag_href ( $url, $offset - $per_page, $this->previous_tag, $search, $anchor);
			$next_tag = $this->_tag_href ( $url, $offset + $per_page, $this->next_tag, $search ,$anchor);
				
			//当前为首页
			if ($offset - $per_page < 0) {
				$first_tag = "<a href='#' class='page_off'>$this->first_tag</a>";
				$previous_tag = "<a href='#' class='page_off'>$this->previous_tag</a>";
			}
			
            //当前为尾页
			if ($offset + $per_page > ($num_pages - 1) * $per_page) {
				$last_tag = "<a href='#' class='page_off'>$this->last_tag</a>";
				$next_tag = "<a href='#' class='page_off'>$this->next_tag</a>";
			}
			$pagebar = $this->_pagebar($url,$total_rows, $per_page ,$offset);
			$output .= $first_tag . $previous_tag;
			$output .= $pagebar;
			$output .= $next_tag . $last_tag;
			$output .= "<label>跳转到第</label><input type='text'  class='style3' id='to_page' value=\"$cur_page\"/><span>页</span><input type='button' class='button2 dojump'  value='跳转'/>";
			$output .= '<input type="hidden" value="' . $per_page . '" id = "hide_perpage">';
			$output .= '<input type="hidden" value="' . $url . '" id = "hide_url">';
			$output .= '<input type="hidden" value="' . $num_pages . '" id = "hide_pagenums">';
			$output .= '<input type="hidden" value="' . $offset . '" id = "hide_offset">';
			$output .= '</div>';
		
		}
		
		return $output;
	}
	
	public function _tag_href($url, $offset=0, $label = '', $search='',$anchor='') {

		return "<a href='".$url."&offset=".$offset."' class='page_off'>$label</a>";
	}
	
	public function _pagebar($url,$total_rows = 0, $per_page = 5 ,$offset){
		
		$num_pages = ceil ( ($total_rows) / $per_page );	
		$cur_page = floor( $offset / $per_page ) + 1;
	    $plus=ceil($this->merpagenum/2);
	    if($this->merpagenum-$plus+$cur_page>$num_pages){
	    	$plus=($this->merpagenum-$num_pages+$cur_page);
	    }
	    $begin=$cur_page-$plus+1;
	    $begin=($begin>=1)?$begin:1;
	    $pagebar='';
	    for($i=$begin;$i<$begin+$this->merpagenum;$i++)
	    {
		   if($i<=$num_pages){
		   		
			     if($i!=$cur_page)
			         $pagebar.=$this->_tag_href($url,($i-1)*$per_page,$i);
			     else 
			         $pagebar.='<a class="page_on">'.$i.'</a>';
		   }else{
		     	break;
		   }
	   }
	   return $pagebar;
	}
}
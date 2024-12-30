var stateDetail = 0;

function click_hide_detail(){
    if(stateDetail == 0){
    document.querySelector('.detail').style.display = 'block';
    stateDetail++;
    }else{
    document.querySelector('.detail').style.display = 'none';
    stateDetail= 0;
}
}


  function scrollToTop() {

    // current screen position
    var cx = document.documentElement.scrollTop;
    var cy = document.documentElement.scrollLeft;

    window.scrollTo({
        top: cx - 800,
        left: cy,
        behavior: "smooth",
    });

  }

  function scrollTodown() {

    // current screen position
    var cx = document.documentElement.scrollTop;
    var cy = document.documentElement.scrollLeft;

    window.scrollTo({
        top: cx + 4000,
        left: cy,
        behavior: "smooth",
    });

  }

  function scrollToleft() {

    // current screen position
    var cx = document.documentElement.scrollTop;
    var cy = document.documentElement.scrollLeft;

    window.scrollTo({
        top: cx ,
        left: cy - 4000,
        behavior: "smooth",
    });

  }

  function scrollToright() {

    // current screen position
    var cx = document.documentElement.scrollTop;
    var cy = document.documentElement.scrollLeft;

    window.scrollTo({
        top: cx ,
        left: cy + 4000,
        behavior: "smooth",
    });

  }

//   document.addEventListener('mousemove', scrollToTop);

  function scrollToStart() {
    window.scrollTo(0,0);
    }

var tbl_hide = document.getElementById('table-hide');

var tbl_state = 0;
function toggle_table() {

    if (tbl_hide.style.display === 'none') {
        tbl_hide.style.display = 'block'; // Or any other desired display value
        document.getElementById('table-hide').innerHTML = 'Hide'; // Change button text to 'Hide'
    } else {
        tbl_hide.style.display = 'none';
        document.getElementById('table-hide').innerHTML = 'Unhide'; // Change button text to 'Unhide'
    }
}

var state_tbl = 0;
function click_hide_tbl(){
    if(state_tbl == 0){
        document.getElementById('table-hide').style.display = 'none';
        state_tbl ++;
    }
   else{
        document.getElementById('table-hide').style.display = 'block';
        state_tbl = 0;
   }
}


var state_tbl_consumtion = 0;
function click_hide_tbl_consumtion(){
    if(state_tbl_consumtion == 0){
        document.getElementById('hide_tbl_consumtion').style.display = 'none';
        state_tbl_consumtion ++;
    }
   else{
        document.getElementById('hide_tbl_consumtion').style.display = 'block';
        state_tbl_consumtion = 0;
   }
}
var state_tbl_consumtion2 = 0;
function click_hide_tbl_consumtion2(){
    if(state_tbl_consumtion2 == 0){
        document.getElementById('table_hide2').style.display = 'none';
        state_tbl_consumtion2 ++;
    }
   else{
        document.getElementById('table_hide2').style.display = 'block';
        state_tbl_consumtion2 = 0;
   }
}
var state_tbl_consumtion3 = 0;
function click_hide_tbl_consumtion3(){
    if(state_tbl_consumtion3 == 0){
        document.getElementById('tbl-hide3').style.display = 'none';
        state_tbl_consumtion3 ++;
    }
   else{
        document.getElementById('tbl-hide3').style.display = 'block';
        state_tbl_consumtion3 = 0;
   }
}


var state_tbl_sale = 0;
function click_hide_tbl_sale(){
    if(state_tbl_sale == 0){
        document.getElementById('hide_sale_s').style.display = 'none';
        state_tbl_sale ++;
    }
   else{
        document.getElementById('hide_sale_s').style.display = 'block';
        state_tbl_sale = 0;
   }
}

var state_tbl_sale3 = 0;
function click_hide_tbl_sale3(){
    if(state_tbl_sale3 == 0){
        document.getElementById('tbl-sale3').style.display = 'none';
        state_tbl_sale3 ++;
    }
   else{
        document.getElementById('tbl-sale3').style.display = 'block';
        state_tbl_sale3 = 0;
   }
}
var loading =  document.querySelector("#loading");
async function traceability(page)
{

    let input_search = document.querySelector('#search_item');
    let search_val = 'NA';
    let page_val = 1;

    if(page){
        page_val = page;
    }
    if(input_search){
        search_val = input_search.value;
    }
    loading.style.display = 'block';
   let url = `/api/traceability/fetch/data`;
    let data = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json", // Specify JSON format for request body
        },
        body: JSON.stringify({
            search: search_val, // Include your payload data
            page: page_val
        }),
        })
        .then((res) => res.json()) // Parse response as JSON
        .catch((error) => {

            console.error("Error:", error); // Log or handle errors
        });

        if(data){
            if(data.data){
                let table = document.querySelector('#item_table');
                table.innerHTML = '';
                let custom = '';
                data.data.forEach((item,index) => {
                    custom += `
                    <tr>
                        <td>${index+1}</td>
                        <td>${item.Item}</td>
                        <td>${item.Description}</td>
                        <td>${item.UOM}</td>
                        <td>${item.type}</td>
                        <td><button  onclick= "fetch_varaint(${index})"><i class="fa-solid fa-plus"></i></button></td>
                    </tr>
                    `;
                });
                table.innerHTML +=custom;

                let pagination = document.querySelector('#pagination');

                pagination.innerHTML = '';
                page = data.page;
                let total_page = data.total_page;
                let left_limit = Math.max(1, page - 5); // Set the left boundary, but not below 1
                let right_limit = Math.min(total_page, page + 5); // Set the right boundary, but not above the total pages
                let custom_page = '';
                let span = document.querySelector("#total_record_show");
                span.textContent = 'Found :'+data.total_record;

                for(let i = left_limit; i <= right_limit; i++){

                    if(i == page){
                        custom_page+= `
                           <li  aria-current="page" class="z-10 flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                                    <button  onclick="traceability(${i})" >${i}</button>
                        </li>
                        `;
                    }else{
                        custom_page+= `
                           <li  class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                              <button onclick="traceability(${i})" >${i}</button>
                        </li>
                        `;
                    }

                }
                pagination.innerHTML += custom_page;

                data_item = data.data;
                loading.style.display = 'none';
            }
            loading.style.display = 'none';
        }
        loading.style.display = 'none';

}

function close_modal(id){
    let modal = document.getElementById(id);
    modal.style.display = 'none';
}

async function  fetch_varaint(no) {
    let modal = document.getElementById("modal_hidden");

    let item = modal.querySelector("#item");
    let variant = modal.querySelector("#variant");
    let from_date = modal.querySelector("#from_date");
    let to_date = modal.querySelector("#to_date");

    // Get the current year
    let currentYear = new Date().getFullYear();

    // Generate dates in YYYY-MM-DD format
    let firstDayOfYear = `${currentYear}-01-01`; // First day of the year
    let lastDayOfYear = `${currentYear}-12-31`; // Last day of the year

    // Set values to the input fields
    from_date.value = firstDayOfYear;
    to_date.value = lastDayOfYear;

    if (data_item) {
        item.value = data_item[no].Item;
    }

    modal.style.display = 'block';

    // Fect Data
    loading.style.display = 'block';
    let url = `/api/traceability/fetch/item/variant`;
    let data = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json", // Specify JSON format for request body
        },
        body: JSON.stringify({
            item: data_item[no].Item
        }),
        })
        .then((res) => res.json()) // Parse response as JSON
        .catch((error) => {

            console.error("Error:", error); // Log or handle errors
        });
        if(data){
            variant.innerHTML = '';
                let custom = '';
                custom+=`
                <option value="NA">All Variant</option>
                <option value=""></option>
                `;
                data.forEach((item) => {
                    custom += `
                       <option value="${item.variant}">${item.variant}</option>
                    `;
                })
                variant.innerHTML += custom;



            variant.removeAttribute('disabled');
            loading.style.display = 'none';
        }else{
            variant.removeAttribute('disabled');
            loading.style.display = 'none';
        }
}


function select_tab(id){
    let order_tab1 = '';
    let order_tab2 = '';
    let order_tab3 = '';
    if(id == 'begin'){
        order_tab1 = 'process';
        order_tab2 = 'end';
        order_tab3 = 'info';
    }else if(id == 'process'){
        order_tab1 = 'begin';
        order_tab2 = 'end';
        order_tab3 = 'info';
    }else if(id == 'end'){
        order_tab1 = 'begin';
        order_tab2 = 'process';
        order_tab3 = 'info';
    }else{
        order_tab1 = 'begin';
        order_tab2 = 'process';
        order_tab3 = 'end';
    }

    // add selected tab to Tab
    let tab_content = document.querySelector('#'+id);
    if (tab_content) {
        if (!tab_content.classList.contains('selected-tab')) {
            tab_content.classList.add('selected-tab');
        }
    }
    // Remove Selected Tab if it has
    let tab_content1 = document.querySelector('#'+order_tab1);
    if (tab_content1) {
        if (tab_content1.classList.contains('selected-tab')) {
            tab_content1.classList.remove('selected-tab');
        }
    }
        // Remove Selected Tab if it has
    let tab_content2 = document.querySelector('#'+order_tab2);
    if (tab_content2) {
        if (tab_content2.classList.contains('selected-tab')) {
            tab_content2.classList.remove('selected-tab');
        }
    }
        // Remove Selected Tab if it has
    let tab_content3 = document.querySelector('#'+order_tab3);
    if (tab_content3) {
        if (tab_content3.classList.contains('selected-tab')) {
            tab_content3.classList.remove('selected-tab');
        }
    }


    let list1 = document.querySelector("#list1");// Info
    let list2 = document.querySelector("#list2");//begin
    let list3 = document.querySelector("#list3");// Proccess
    let list4 = document.querySelector("#list4");// End Stock

    if(id == 'begin'){
        list1.style.color = "black";
        list2.style.color = "blue";
        list3.style.color = "black";
        list4.style.color = "black";
    }else if(id == 'process'){
        list1.style.color = "black";
        list2.style.color = "black";
        list3.style.color = "blue";
        list4.style.color = "black";
    }else if(id == 'end'){
        list1.style.color = "black";
        list2.style.color = "black";
        list3.style.color = "black";
        list4.style.color = "blue";
    }else{
        list1.style.color = "blue";
        list2.style.color = "black";
        list3.style.color = "black";
        list4.style.color = "black";
    }
}
function view_detail()
{
    let btn_submit_form = document.querySelector("#detail_data");

    btn_submit_form.click();
}

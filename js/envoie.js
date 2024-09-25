function getParams() {
    const params = new URLSearchParams(window.location.search);
    return {
        weight: params.get('weight'),
        type: params.get('type'),
        tarif: params.get('tarif'),
    };
}


window.onload=function(){
    
    const { weight, type,tarif } = getParams();
    console.log(weight,type,tarif);
    document.querySelector('#asideBarLeft h2').innerHTML=type+':'+' '+weight+'g';
    document.querySelector('#asideBarLeft span').innerHTML='1 x '+type+': '+tarif+'DH';
    document.querySelector('#total').innerHTML=tarif;
    let checkSms=document.getElementById('optionSms');
    checkSms.onchange=()=>{
    if(checkSms.checked==1){
        spanSms=document.createElement('span');
        spanSms.setAttribute('id', 'smsOption');
        spanSms.innerHTML='1 x '+'SMS:'+'1DH';
        document.querySelector('#asideBarLeft').appendChild(spanSms);
        document.getElementById('tarif').value=parseInt(document.getElementById('tarif').value)+1;
        document.querySelector('#total').innerHTML=parseInt( document.querySelector('#total').innerHTML)+1;
        checkSms.value=1;
    }
    else{
        spanSms=document.getElementById('smsOption');
        document.getElementById('tarif').value=parseInt(document.getElementById('tarif').value)-1;
        document.querySelector('#total').innerHTML=parseInt( document.querySelector('#total').innerHTML)-1;
        checkSms.value=0;
        spanSms.remove();
    }
}
}
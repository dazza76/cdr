$(function(){
  $('select[multiple]')
    .find('option').text(function(index, text){
      // trim whitespace
      return text.replace(/^\s*/, '').replace(/\s*$/, '');
    }).end()
    .dropdownchecklist({
      firstItemChecksAll: true,
      maxDropHeight: 400,
      width: 150,
      explicitClose: 'Закрыть',
      textFormatFunction: function(options) {
        var selectedOptions = options.filter(":selected");
        var countOfSelected = selectedOptions.size();
        var size = options.size();
        var allText = options.filter(":first").text();
        switch(countOfSelected) {
           case 0: return "Выберите значения";
           case 1: return selectedOptions.text();
           case options.size(): return allText;
           default: return "Выбрано: " + countOfSelected;
        }
      },
      onComplete: function(selector) {
        if (selector.options[0].selected) {
          $('select[name="'+selector.name+'"] option').removeAttr("selected");
        }
      }
    });
  $('select[multiple]').each(function(){
    if (this.selectedIndex == 0) $(this).find('option').removeAttr("selected");
  });
});

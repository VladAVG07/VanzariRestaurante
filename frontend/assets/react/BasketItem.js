// BasketItem.js
const BasketItem = (item) => {
  
  return React.createElement('li', { className: 'item' },
    React.createElement('div', { className: 'd-flex ftco-animate fadeInUp ftco-animated text align-items-center', 'data-id': '7' },
      React.createElement('div', { className: 'col-5' },
        React.createElement('h5', null,
          React.createElement('span', null, item.nume)
        )
      ),
      React.createElement('div', { className: 'col-4 cos-produs align-items-center' },
        React.createElement('div', { className: 'd-none field-basketitem-idprodus' },
          React.createElement('input', { type: 'hidden', id: 'basketitem-idprodus', className: 'form-control', name: 'BasketItem[idProdus]', value: item.id }),
          React.createElement('div', { className: 'invalid-feedback' })
        ),
        React.createElement('div', { className: 'form-group field-basketitem-cantitate' },
          React.createElement('div', { className: 'input-group bootstrap-touchspin bootstrap-touchspin-injected' },
            React.createElement('span', { className: 'input-group-btn input-group-prepend' },
              React.createElement('button', { className: 'h-50 btn btn-sm btn-info bootstrap-touchspin-down', type: 'button' }, '-')
            ),
            React.createElement('input', { type: 'text', id: 'basketitem-cantitate', className: 'cos-produs-input form-control', name: 'BasketItem[cantitate]', value: item.cantitate, 'data-price': item.pret, 'data-produs': item.id, 'data-krajee-touchspin': 'TouchSpin_db438be9' }),
            React.createElement('span', { className: 'input-group-btn input-group-append' },
              React.createElement('button', { className: 'h-50 btn btn-block btn-sm btn-primary bootstrap-touchspin-up', type: 'button' }, '+')
            )
          ),
          React.createElement('div', { className: 'invalid-feedback' })
        )
      ),
      React.createElement('div', { className: 'col-3 text-left' },
        React.createElement('span', { className: 'price' }, item.pret)
      )
    )
  );
};



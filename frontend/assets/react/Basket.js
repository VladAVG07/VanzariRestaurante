// Define the Basket component
const Basket = ({ items }) => {
  const basketItems = items.map((item, index) => (
    React.createElement(BasketItem, { key: index,id:item.id, nume: item.nume, cantitate: item.cantitate, pret: item.pret })
  ));

  return React.createElement('div', { className: 'cart-items item' },
    React.createElement('ul', { className: 'list-unstyled' }, basketItems)
  );
};
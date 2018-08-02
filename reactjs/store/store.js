// Default redux createStore function.
import { createStore, applyMiddleware } from 'redux';

// Debug.
import { composeWithDevTools } from 'redux-devtools-extension';

// Sagas!
import createSagaMiddleware from 'redux-saga';

// Import all our custom sagas.
import sagas from './sagas';

// Import all our custom reducers.
import reducers from './reducers';

// Create a saga middleware.
const sagaMiddleware = createSagaMiddleware();

function configureStore(initialState) {
  // Build store.
  // TODO: Disable dev tools on production.
  const store = createStore(
    reducers,
    initialState,
    composeWithDevTools(applyMiddleware(sagaMiddleware)),
  );

  store.runSagaTask = () => {
    store.sagaTask = sagaMiddleware.run(sagas);
  };

  store.runSagaTask();
  return store;
}

export default configureStore;

import {Module, Store as VuexStore, CommitOptions, DispatchOptions} from 'vuex'
import {RootState} from '../../index'
import {state} from './state'
import {actions, Actions} from './actions'
import {mutations, Mutations} from './mutations'
import {getters, Getters} from './getters'

import type {State} from './state'

export {State};

export type AuthStudentStore<S = State> = Omit<VuexStore<S>, 'getters' | 'commit' | 'dispatch'> & {
    commit<K extends keyof Mutations, P extends Parameters<Mutations[K]>[1]>(
        key: K,
        payload: P,
        options?: CommitOptions
    ): ReturnType<Mutations[K]>;
} & {
    // TODO: How to configure a module without any actions?
    dispatch<K extends keyof Actions>(
        key: K,
        payload: Parameters<Actions[K]>[1],
        options?: DispatchOptions
    ): ReturnType<Actions[K]>;
} & {
    getters: {
        [K in keyof Getters]: ReturnType<Getters[K]>
    }
};

export const store: Module<State, RootState> = {
    state,
    mutations,
    getters,
    actions,
    namespaced: true,
};

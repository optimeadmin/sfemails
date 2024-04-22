import React, { createContext, PropsWithChildren, useContext } from 'react'

type LocaleContextType = {}

const LocaleContext = createContext<LocaleContextType | null>(null)

type LocaleProviderProps = {
  locale: string,
  locales: string[],
} & PropsWithChildren

export function LocaleProvider ({ children, locale, locales }: LocaleProviderProps) {
  return (
    <LocaleContext.Provider value={{ locale, locales }}>
      {children}
    </LocaleContext.Provider>
  )
}

export function useLocales (): LocaleContextType {
  const context = useContext(LocaleContext)

  if (!context) {
    throw new Error('useLocales can be used into <LocaleProvider />')
  }

  return context
}
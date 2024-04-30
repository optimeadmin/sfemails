import { useEffect, useState } from 'react'

export type ToggleSelectedType<Item> = (item: Item) => void

export function useSelectedItems<Key = string, Item = any> (
  items: Item[] | null,
  getItemKey: (item: Item) => Key | false
) {
  const [selectedItems, setSelectedItems] = useState<Key[]>([])
  const selectableKeys = (items?.map(getItemKey).filter(Boolean) ?? []) as Key[]
  const isSelectedAll = selectableKeys.length > 0 && selectableKeys.length === selectedItems.length

  useEffect(() => {
    setSelectedItems([])
  }, [items])

  function isItemSelected (item: Item) {
    const key = getItemKey(item)
    return key && selectedItems.includes(key)
  }

  function toggleSelectedItem (item: Item) {
    const itemKey = getItemKey(item)!

    if (itemKey === false) return

    if (isItemSelected(item)) {
      setSelectedItems(prev => prev.filter(key => key !== itemKey))
    } else {
      setSelectedItems(prev => [...prev, itemKey])
    }
  }

  function toggleAll () {
    isSelectedAll
      ? setSelectedItems([])
      : setSelectedItems(selectableKeys)
  }

  return {
    selectedItems,
    isItemSelected,
    isSelectedAll,
    toggleSelectedItem,
    toggleAll,
  }
}
import React from 'react'

type NoItemsProps = {
  isLoading: boolean,
  items: Array<unknown> | null | undefined,
  colSpan?: number,
}

export function NoItems ({ isLoading, items, colSpan = 100 }: NoItemsProps) {
  if (isLoading) return null
  if (items && items.length > 0) return null

  return (
    <tr>
      <td colSpan={colSpan} className="text-center">
        <div className="py-2">
          No items found
        </div>
      </td>
    </tr>
  )
}
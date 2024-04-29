import React, { createContext, useCallback, useContext, useRef, useState } from 'react'
import { Button, ButtonProps, OverlayTrigger, OverlayTriggerProps } from 'react-bootstrap'

type HidePopover = () => void
const Context = createContext<HidePopover | null>(null)

type Props = Omit<OverlayTriggerProps, 'show' | 'trigger' | 'onExited' | 'onEntered' | 'onToggle'>

export function AppOverlayTrigger (props: Props) {
  const [show, setShow] = useState(props.defaultShow ?? false)
  const $popover = useRef<Element | null>(null)

  const hide = useCallback(() => {
    setShow(false)
  }, [])

  const onDocumentClick = useCallback((event: Event) => {
    if (!$popover.current || !(event.target instanceof HTMLElement)) {
      hide()
    }

    const target = event.target as HTMLElement

    if (target !== $popover.current && !$popover.current!.contains(target)) {
      hide()
    }
  }, [$popover, hide])

  function onEnter () {
    $popover.current = document.querySelector('.popover.show')
    document.addEventListener('click', onDocumentClick)
  }

  function onExit () {
    document.removeEventListener('click', onDocumentClick)
    $popover.current = null
  }

  return (
    <Context.Provider value={hide}>
      <OverlayTrigger
        {...props}
        show={show}
        trigger="click"
        onToggle={show => setShow(show)}
        onEntered={onEnter}
        onExited={onExit}
      />
    </Context.Provider>
  )
}

export function useHidePopover (): HidePopover {
  return useContext(Context)!
}

export function HidePopoverButton (props: ButtonProps) {
  const hide = useHidePopover()

  return <Button {...props} onClick={hide}/>
}
